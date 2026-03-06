<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * Admin\AuthController — admin login/logout.
 *
 * IMPORTANT: checks .env credentials ONLY (no database).
 * This is intentional — admin is not stored in the DB.
 */
class AuthController extends BaseController
{
    public function loginForm()
    {
        // If already admin, go to dashboard
        if (session()->get('is_admin')) {
            return redirect()->to('/admin');
        }
        return view('admin/login');
    }

    public function login()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Compare against .env — admin is never in the database
        if ($email === $_ENV['ADMIN_EMAIL'] && $password === $_ENV['ADMIN_PASSWORD']) {
            session()->set([
                'is_admin'   => true,
                'admin_name' => 'Administrator',
            ]);
            return redirect()->to('/admin')->with('success', 'Welcome, Admin!');
        }

        return redirect()->back()->with('error', 'Invalid admin credentials.');
    }

    public function logout()
    {
        session()->remove(['is_admin', 'admin_name']);
        return redirect()->to('/admin/login')->with('success', 'Logged out.');
    }
}
