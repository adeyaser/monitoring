<?php

namespace App\Controllers\Acl;

use App\Controllers\BaseController;
use App\Models\AclGroupModel;
use App\Models\AclMenuModel;
use App\Models\AclGroupMenuModel;

class Permissions extends BaseController
{
    protected $groupModel;
    protected $menuModel;
    protected $groupMenuModel;

    public function __construct()
    {
        $this->groupModel = new AclGroupModel();
        $this->menuModel = new AclMenuModel();
        $this->groupMenuModel = new AclGroupMenuModel();
    }

    public function index()
    {
        $permCheck = $this->requirePermission('acl_permissions');
        if ($permCheck) return $permCheck;

        $groups = $this->groupModel->getActiveGroups();
        $menus = $this->menuModel->getActiveMenus();
        
        // Get permissions for each group
        $permissions = [];
        foreach ($groups as $group) {
            $groupPerms = $this->groupMenuModel->getPermissionsByGroup($group['id']);
            foreach ($groupPerms as $perm) {
                $permissions[$group['id']][$perm['menu_id']] = $perm;
            }
        }

        $data = array_merge($this->getViewData(), [
            'title' => 'Hak Akses Group',
            'pageTitle' => 'Hak Akses Group',
            'groups' => $groups,
            'menus' => $menus,
            'permissions' => $permissions
        ]);

        return view('acl/permissions/index', $data);
    }

    public function update()
    {
        $permCheck = $this->requirePermission('acl_permissions', 'can_update');
        if ($permCheck) return $permCheck;

        $groupId = $this->request->getPost('group_id');
        $menuPermissions = $this->request->getPost('permissions') ?? [];

        if (!$groupId) {
            return redirect()->back()->with('error', 'Group tidak dipilih');
        }

        // Prepare permissions data
        $permissionsData = [];
        foreach ($menuPermissions as $menuId => $perms) {
            $permissionsData[] = [
                'group_id' => $groupId,
                'menu_id' => $menuId,
                'can_view' => isset($perms['can_view']) ? 1 : 0,
                'can_create' => isset($perms['can_create']) ? 1 : 0,
                'can_update' => isset($perms['can_update']) ? 1 : 0,
                'can_delete' => isset($perms['can_delete']) ? 1 : 0
            ];
        }

        $this->groupMenuModel->setPermissionsBulk($groupId, $permissionsData);

        return redirect()->to('/acl/permissions')->with('success', 'Hak akses berhasil diperbarui');
    }
}
