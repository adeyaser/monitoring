<?php

namespace App\Controllers;

use App\Models\AclUserModel;
use App\Models\AclGroupModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $groupModel;

    public function __construct()
    {
        $this->userModel = new AclUserModel();
        $this->groupModel = new AclGroupModel();
    }

    public function login()
    {
        // If already logged in, redirect to dashboard
        if ($this->session && $this->session->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Login - Dashboard Monitoring',
            'settings' => $this->settings ?? []
        ];

        return view('auth/login', $data);
    }

    public function authenticate()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Check by username or email
        $user = $this->userModel->getByUsername($username);
        if (!$user) {
            $user = $this->userModel->getByEmail($username);
        }

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Username atau email tidak ditemukan');
        }

        // Check if active
        if (!$user['is_active']) {
            return redirect()->back()->withInput()->with('error', 'Akun Anda tidak aktif. Hubungi administrator.');
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Password salah');
        }

        // Get group info
        $group = $this->groupModel->find($user['group_id']);

        // Set session
        $sessionData = [
            'userId' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'fullName' => $user['full_name'],
            'groupId' => $user['group_id'],
            'groupName' => $group['group_name'],
            'groupCode' => $group['group_code'],
            'isLoggedIn' => true
        ];

        $this->session->set($sessionData);

        // Update last login
        $this->userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);

        return redirect()->to('/dashboard')->with('success', 'Selamat datang, ' . $user['full_name']);
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah logout');
    }
}
