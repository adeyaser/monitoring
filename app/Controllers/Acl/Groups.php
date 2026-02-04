<?php

namespace App\Controllers\Acl;

use App\Controllers\BaseController;
use App\Models\AclGroupModel;

class Groups extends BaseController
{
    protected $groupModel;

    public function __construct()
    {
        $this->groupModel = new AclGroupModel();
    }

    public function index()
    {
        $permCheck = $this->requirePermission('acl_groups');
        if ($permCheck) return $permCheck;

        $data = array_merge($this->getViewData(), [
            'title' => 'Manajemen Group',
            'pageTitle' => 'Manajemen Group',
            'groups' => $this->groupModel->getGroupWithUserCount()
        ]);

        return view('acl/groups/index', $data);
    }

    public function create()
    {
        $permCheck = $this->requirePermission('acl_groups', 'can_create');
        if ($permCheck) return $permCheck;

        $data = array_merge($this->getViewData(), [
            'title' => 'Tambah Group',
            'pageTitle' => 'Tambah Group'
        ]);

        return view('acl/groups/form', $data);
    }

    public function store()
    {
        $permCheck = $this->requirePermission('acl_groups', 'can_create');
        if ($permCheck) return $permCheck;

        $rules = [
            'group_name' => 'required|min_length[3]',
            'group_code' => 'required|alpha_dash|is_unique[acl_groups.group_code]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'group_name' => $this->request->getPost('group_name'),
            'group_code' => strtolower($this->request->getPost('group_code')),
            'description' => $this->request->getPost('description'),
            'is_active' => $this->request->getPost('is_active') ?? 1
        ];

        $this->groupModel->insert($data);

        return redirect()->to('/acl/groups')->with('success', 'Group berhasil ditambahkan');
    }

    public function edit($id)
    {
        $permCheck = $this->requirePermission('acl_groups', 'can_update');
        if ($permCheck) return $permCheck;

        $group = $this->groupModel->find($id);
        if (!$group) {
            return redirect()->to('/acl/groups')->with('error', 'Group tidak ditemukan');
        }

        $data = array_merge($this->getViewData(), [
            'title' => 'Edit Group',
            'pageTitle' => 'Edit Group',
            'group' => $group
        ]);

        return view('acl/groups/form', $data);
    }

    public function update($id)
    {
        $permCheck = $this->requirePermission('acl_groups', 'can_update');
        if ($permCheck) return $permCheck;

        $group = $this->groupModel->find($id);
        if (!$group) {
            return redirect()->to('/acl/groups')->with('error', 'Group tidak ditemukan');
        }

        $rules = [
            'group_name' => 'required|min_length[3]',
            'group_code' => "required|alpha_dash|is_unique[acl_groups.group_code,id,{$id}]"
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'group_name' => $this->request->getPost('group_name'),
            'group_code' => strtolower($this->request->getPost('group_code')),
            'description' => $this->request->getPost('description'),
            'is_active' => $this->request->getPost('is_active') ?? 1
        ];

        $this->groupModel->update($id, $data);

        return redirect()->to('/acl/groups')->with('success', 'Group berhasil diperbarui');
    }

    public function delete($id)
    {
        $permCheck = $this->requirePermission('acl_groups', 'can_delete');
        if ($permCheck) return $permCheck;

        $group = $this->groupModel->find($id);
        if (!$group) {
            return $this->response->setJSON(['success' => false, 'message' => 'Group tidak ditemukan']);
        }

        // Prevent deleting superadmin group
        if ($group['group_code'] === 'superadmin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak dapat menghapus group Super Administrator']);
        }

        // Prevent deleting own group
        if ($group['id'] == $this->userData['group_id']) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak dapat menghapus group sendiri']);
        }

        $this->groupModel->delete($id);

        return $this->response->setJSON(['success' => true, 'message' => 'Group berhasil dihapus']);
    }
}
