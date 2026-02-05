<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\AclMenuModel;
use App\Models\AclGroupMenuModel;
use App\Models\AppSettingModel;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['url', 'form', 'text'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    protected $session;
    protected $userData;
    protected $menuData;
    protected $settings;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->session = \Config\Services::session();
        
        // Load user data or set guest defaults
        if ($this->session->get('isLoggedIn')) {
            $this->userData = [
                'id' => $this->session->get('userId'),
                'username' => $this->session->get('username'),
                'full_name' => $this->session->get('fullName'),
                'email' => $this->session->get('email'),
                'group_id' => $this->session->get('groupId'),
                'group_name' => $this->session->get('groupName'),
                'group_code' => $this->session->get('groupCode'),
            ];
            
            // Load menu data for specific group
            $menuModel = new AclMenuModel();
            $this->menuData = $menuModel->getMenusForGroup($this->userData['group_id']);
        } else {
            // Guest mode: Load all active menus but set can_view to true for display
            $menuModel = new AclMenuModel();
            $rawMenus = $menuModel->getActiveMenus();
            
            // Mock permissions for guest to see menus
            foreach ($rawMenus as &$m) {
                $m['can_view'] = 1;
            }
            
            // Re-build tree manually for guest
            $this->menuData = $this->invokePrivateMethod($menuModel, 'buildTree', [$rawMenus]);
        }
        
        // Load app settings
        $settingModel = new AppSettingModel();
        $this->settings = $settingModel->getAllSettings();
    }

    /**
     * Helper to invoke private methods (for buildTree in AclMenuModel)
     */
    private function invokePrivateMethod($object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
 
        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Check if user is logged in
     */
    protected function isLoggedIn(): bool
    {
        return $this->session->get('isLoggedIn') === true;
    }

    /**
     * Check user permission
     */
    protected function hasPermission($menuCode, $permission = 'can_view'): bool
    {
        if (!$this->isLoggedIn()) {
            return false;
        }
        
        $groupMenuModel = new AclGroupMenuModel();
        return $groupMenuModel->hasPermission($this->userData['group_id'], $menuCode, $permission);
    }

    /**
     * Require login - redirect if not logged in
     */
    protected function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        return null;
    }

    /**
     * Require permission - redirect if no permission
     */
    protected function requirePermission($menuCode, $permission = 'can_view')
    {
        $loginCheck = $this->requireLogin();
        if ($loginCheck) {
            return $loginCheck;
        }

        if (!$this->hasPermission($menuCode, $permission)) {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        return null;
    }

    /**
     * Get common view data
     */
    protected function getViewData(): array
    {
        return [
            'user' => $this->userData,
            'menus' => $this->menuData,
            'settings' => $this->settings,
        ];
    }
}
