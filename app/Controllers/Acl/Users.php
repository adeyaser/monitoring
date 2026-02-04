<?php

namespace App\Controllers\Acl;

use App\Controllers\BaseController;
use App\Models\AclUserModel;
use App\Models\AclGroupModel;

class Users extends BaseController
{
    protected $userModel;
    protected $groupModel;

    public function __construct()
    {
        $this->userModel = new AclUserModel();
        $this->groupModel = new AclGroupModel();
    }

    public function index()
    {
        $permCheck = $this->requirePermission('acl_users');
        if ($permCheck) return $permCheck;

        $data = array_merge($this->getViewData(), [
            'title' => 'Manajemen User',
            'pageTitle' => 'Manajemen User',
            'users' => $this->userModel->getUserWithGroup()
        ]);

        return view('acl/users/index', $data);
    }

    public function create()
    {
        $permCheck = $this->requirePermission('acl_users', 'can_create');
        if ($permCheck) return $permCheck;

        $data = array_merge($this->getViewData(), [
            'title' => 'Tambah User',
            'pageTitle' => 'Tambah User',
            'groups' => $this->groupModel->getActiveGroups()
        ]);

        return view('acl/users/form', $data);
    }

    public function store()
    {
        $permCheck = $this->requirePermission('acl_users', 'can_create');
        if ($permCheck) return $permCheck;

        $rules = [
            'username' => 'required|min_length[3]|is_unique[acl_users.username]',
            'email' => 'required|valid_email|is_unique[acl_users.email]',
            'password' => 'required|min_length[6]',
            'full_name' => 'required|min_length[3]',
            'group_id' => 'required|is_not_unique[acl_groups.id]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'full_name' => $this->request->getPost('full_name'),
            'phone' => $this->request->getPost('phone'),
            'group_id' => $this->request->getPost('group_id'),
            'is_active' => $this->request->getPost('is_active') ?? 1
        ];

        $this->userModel->insert($data);

        return redirect()->to('/acl/users')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $permCheck = $this->requirePermission('acl_users', 'can_update');
        if ($permCheck) return $permCheck;

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/acl/users')->with('error', 'User tidak ditemukan');
        }

        $data = array_merge($this->getViewData(), [
            'title' => 'Edit User',
            'pageTitle' => 'Edit User',
            'user' => $user,
            'groups' => $this->groupModel->getActiveGroups()
        ]);

        return view('acl/users/form', $data);
    }

    public function update($id)
    {
        $permCheck = $this->requirePermission('acl_users', 'can_update');
        if ($permCheck) return $permCheck;

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/acl/users')->with('error', 'User tidak ditemukan');
        }

        $rules = [
            'username' => "required|min_length[3]|is_unique[acl_users.username,id,{$id}]",
            'email' => "required|valid_email|is_unique[acl_users.email,id,{$id}]",
            'full_name' => 'required|min_length[3]',
            'group_id' => 'required|is_not_unique[acl_groups.id]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'full_name' => $this->request->getPost('full_name'),
            'phone' => $this->request->getPost('phone'),
            'group_id' => $this->request->getPost('group_id'),
            'is_active' => $this->request->getPost('is_active') ?? 1
        ];

        // Update password only if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = $password;
        }

        $this->userModel->update($id, $data);

        return redirect()->to('/acl/users')->with('success', 'User berhasil diperbarui');
    }

    public function delete($id)
    {
        $permCheck = $this->requirePermission('acl_users', 'can_delete');
        if ($permCheck) return $permCheck;

        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User tidak ditemukan']);
        }

        // Prevent deleting yourself
        if ($user['id'] == $this->userData['id']) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak dapat menghapus akun sendiri']);
        }

        $this->userModel->delete($id);

        return $this->response->setJSON(['success' => true, 'message' => 'User berhasil dihapus']);
    }

    public function apiList()
    {
        $users = $this->userModel->getUserWithGroup();
        return $this->response->setJSON(['success' => true, 'data' => $users]);
    }
}
