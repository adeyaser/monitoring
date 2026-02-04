<?php

namespace App\Controllers;

use App\Models\AclUserModel;

class Profile extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new AclUserModel();
    }

    public function index()
    {
        $userId = session()->get('userId');
        if (!$userId) return redirect()->to('/login');

        $user = $this->userModel->find($userId);

        $data = array_merge($this->getViewData(), [
            'title' => 'My Profile',
            'pageTitle' => 'My Profile',
            'user' => $user
        ]);

        return view('profile/index', $data);
    }

    public function update()
    {
        $userId = session()->get('userId');
        if (!$userId) return redirect()->to('/login');

        $rules = [
            'full_name' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[acl_users.email,id,'.$userId.']',
            'username' => 'required|min_length[3]|is_unique[acl_users.username,id,'.$userId.']'
        ];

        // Password change logic
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = 'min_length[6]';
            $rules['confirm_password'] = 'matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
        ];

        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $this->userModel->update($userId, $data);

        // Update session
        session()->set([
            'username' => $data['username'],
            'email' => $data['email'],
            'fullName' => $data['full_name']
        ]);

        return redirect()->to('/profile')->with('success', 'Profile updated successfully');
    }
}
