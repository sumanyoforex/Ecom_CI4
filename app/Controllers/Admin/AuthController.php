<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * Admin auth via environment credentials.
 */
class AuthController extends BaseController
{
    public function loginForm()
    {
        if (session()->get('is_admin')) {
            return redirect()->to('/admin');
        }

        return view('admin/login');
    }

    public function login()
    {
        $email = strtolower(trim((string)$this->request->getPost('email')));
        $password = (string)$this->request->getPost('password');

        $throttler = service('throttler');
        $attemptKey = 'admin_login_' . sha1($this->request->getIPAddress() . '|' . $email);
        if (!$throttler->check($attemptKey, 5, 600)) {
            return redirect()->back()->with('error', 'Too many attempts. Please wait 10 minutes.');
        }

        if ($email === strtolower((string)env('ADMIN_EMAIL')) && $password === (string)env('ADMIN_PASSWORD')) {
            session()->regenerate(true);
            session()->set([
                'is_admin' => true,
                'admin_name' => 'Administrator',
            ]);

            return redirect()->to('/admin')->with('success', 'Welcome, Admin!');
        }

        return redirect()->back()->with('error', 'Invalid admin credentials.');
    }

    public function logout()
    {
        session()->remove(['is_admin', 'admin_name']);
        return redirect()->to('/')->with('success', 'Logged out of admin panel.');
    }
}

