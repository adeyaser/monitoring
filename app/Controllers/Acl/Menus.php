<?php

namespace App\Controllers\Acl;

use App\Controllers\BaseController;
use App\Models\AclMenuModel;

class Menus extends BaseController
{
    protected $menuModel;

    public function __construct()
    {
        $this->menuModel = new AclMenuModel();
    }

    public function index()
    {
        $permCheck = $this->requirePermission('acl_menus');
        if ($permCheck) return $permCheck;

        $data = array_merge($this->getViewData(), [
            'title' => 'Manajemen Menu',
            'pageTitle' => 'Manajemen Menu',
            'menus' => $this->menuModel->getMenuTree()
        ]);

        return view('acl/menus/index', $data);
    }

    public function create()
    {
        $permCheck = $this->requirePermission('acl_menus', 'can_create');
        if ($permCheck) return $permCheck;

        $data = array_merge($this->getViewData(), [
            'title' => 'Tambah Menu',
            'pageTitle' => 'Tambah Menu',
            'parentMenus' => $this->menuModel->getParentMenus()
        ]);

        return view('acl/menus/form', $data);
    }

    public function store()
    {
        $permCheck = $this->requirePermission('acl_menus', 'can_create');
        if ($permCheck) return $permCheck;

        $rules = [
            'menu_name' => 'required|min_length[2]',
            'menu_code' => 'required|alpha_dash|is_unique[acl_menus.menu_code]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'menu_name' => $this->request->getPost('menu_name'),
            'menu_code' => strtolower($this->request->getPost('menu_code')),
            'menu_url' => $this->request->getPost('menu_url'),
            'menu_icon' => $this->request->getPost('menu_icon'),
            'parent_id' => $this->request->getPost('parent_id') ?: null,
            'menu_order' => $this->request->getPost('menu_order') ?? 0,
            'is_active' => $this->request->getPost('is_active') ?? 1
        ];

        $this->menuModel->insert($data);

        return redirect()->to('/acl/menus')->with('success', 'Menu berhasil ditambahkan');
    }

    public function edit($id)
    {
        $permCheck = $this->requirePermission('acl_menus', 'can_update');
        if ($permCheck) return $permCheck;

        $menu = $this->menuModel->find($id);
        if (!$menu) {
            return redirect()->to('/acl/menus')->with('error', 'Menu tidak ditemukan');
        }

        $data = array_merge($this->getViewData(), [
            'title' => 'Edit Menu',
            'pageTitle' => 'Edit Menu',
            'menu' => $menu,
            'parentMenus' => $this->menuModel->getParentMenus()
        ]);

        return view('acl/menus/form', $data);
    }

    public function update($id)
    {
        $permCheck = $this->requirePermission('acl_menus', 'can_update');
        if ($permCheck) return $permCheck;

        $menu = $this->menuModel->find($id);
        if (!$menu) {
            return redirect()->to('/acl/menus')->with('error', 'Menu tidak ditemukan');
        }

        $rules = [
            'menu_name' => 'required|min_length[2]',
            'menu_code' => "required|alpha_dash|is_unique[acl_menus.menu_code,id,{$id}]"
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'menu_name' => $this->request->getPost('menu_name'),
            'menu_code' => strtolower($this->request->getPost('menu_code')),
            'menu_url' => $this->request->getPost('menu_url'),
            'menu_icon' => $this->request->getPost('menu_icon'),
            'parent_id' => $this->request->getPost('parent_id') ?: null,
            'menu_order' => $this->request->getPost('menu_order') ?? 0,
            'is_active' => $this->request->getPost('is_active') ?? 1
        ];

        $this->menuModel->update($id, $data);

        return redirect()->to('/acl/menus')->with('success', 'Menu berhasil diperbarui');
    }

    public function delete($id)
    {
        $permCheck = $this->requirePermission('acl_menus', 'can_delete');
        if ($permCheck) return $permCheck;

        $menu = $this->menuModel->find($id);
        if (!$menu) {
            return $this->response->setJSON(['success' => false, 'message' => 'Menu tidak ditemukan']);
        }

        $this->menuModel->delete($id);

        return $this->response->setJSON(['success' => true, 'message' => 'Menu berhasil dihapus']);
    }
}
