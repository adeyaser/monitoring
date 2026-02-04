<?php

namespace App\Controllers;

use App\Models\AppSettingModel;

class Settings extends BaseController
{
    protected $settingModel;

    public function __construct()
    {
        $this->settingModel = new AppSettingModel();
    }

    public function index()
    {
        $permCheck = $this->requirePermission('settings');
        if ($permCheck) return $permCheck;

        $data = array_merge($this->getViewData(), [
            'title' => 'Pengaturan Sistem',
            'pageTitle' => 'Pengaturan Sistem',
            'allSettings' => $this->settingModel->findAll()
        ]);

        return view('settings/index', $data);
    }

    public function update()
    {
        $permCheck = $this->requirePermission('settings', 'can_update');
        if ($permCheck) return $permCheck;

        $settings = $this->request->getPost('settings');

        if ($settings && is_array($settings)) {
            foreach ($settings as $key => $value) {
                $this->settingModel->setSetting($key, $value);
            }
        }

        return redirect()->to('/settings')->with('success', 'Pengaturan berhasil disimpan');
    }
}
