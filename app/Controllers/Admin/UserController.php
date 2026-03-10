<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

/**
 * Admin user management (list + edit customers).
 */
class UserController extends BaseController
{
    public function index()
    {
        return view('admin/user/index', [
            'users' => (new UserModel())->orderBy('created_at', 'DESC')->findAll(),
        ]);
    }

    public function edit(int $id)
    {
        $user = (new UserModel())->find($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User not found.');
        }

        return view('admin/user/edit', ['user' => $user]);
    }

    public function update(int $id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User not found.');
        }

        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|is_unique[users.email,id,' . $id . ']',
            'role' => 'required|in_list[customer,support,manager]',
        ];

        $password = (string)$this->request->getPost('password');
        if ($password !== '') {
            $rules['password'] = 'required|min_length[8]|max_length[72]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $data = [
            'name' => trim((string)$this->request->getPost('name')),
            'email' => strtolower(trim((string)$this->request->getPost('email'))),
            'role' => (string)$this->request->getPost('role'),
        ];

        if ($password !== '') {
            $data['password'] = $password;
        }

        $userModel->update($id, $data);

        return redirect()->to('/admin/users')->with('success', 'User details updated.');
    }
}



