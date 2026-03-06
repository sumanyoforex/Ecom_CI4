<?php

namespace App\Controllers;

use App\Models\UserModel;

/**
 * AuthController — handles customer registration and login.
 *
 * LOGIN FLOW:
 *  1. POST /login → check .env admin credentials FIRST
 *  2. If admin match → set admin session → redirect /admin
 *  3. Else → check users table → issue JWT cookie + session
 */
class AuthController extends BaseController
{
    // ----------------------------------------------------------------
    // Registration
    // ----------------------------------------------------------------

    public function registerForm()
    {
        return view('auth/register');
    }

    public function register()
    {
        $rules = [
            'name'     => 'required|min_length[2]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return view('auth/register', ['errors' => $this->validator->getErrors()]);
        }

        $model = new UserModel();
        $userId = $model->insert([
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'), // hashed in model
        ]);

        // Issue JWT and set cookie for auto-login after registration
        $token = jwt_generate(['user_id' => $userId, 'name' => $this->request->getPost('name')]);
        $this->setAuthCookie($token);

        session()->set([
            'user_id'   => $userId,
            'user_name' => $this->request->getPost('name'),
        ]);

        return redirect()->to('/')->with('success', 'Welcome! Account created.');
    }

    // ----------------------------------------------------------------
    // Login
    // ----------------------------------------------------------------

    public function loginForm()
    {
        return view('auth/login');
    }

    public function login()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // STEP 1: Check .env admin credentials FIRST
        if ($email === $_ENV['ADMIN_EMAIL'] && $password === $_ENV['ADMIN_PASSWORD']) {
            session()->set([
                'is_admin'   => true,
                'admin_name' => 'Admin',
            ]);
            return redirect()->to('/admin');
        }

        // STEP 2: Check the database for a customer account
        $model = new UserModel();
        $user  = $model->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Invalid email or password.');
        }

        // Issue JWT token (stored in HttpOnly cookie for security)
        $token = jwt_generate(['user_id' => $user['id'], 'name' => $user['name']]);
        $this->setAuthCookie($token);

        // Also set session so filters can check quickly
        session()->set([
            'user_id'   => $user['id'],
            'user_name' => $user['name'],
        ]);

        // Handle "remember me" checkbox
        if ($this->request->getPost('remember')) {
            $token30d = jwt_generate(['user_id' => $user['id'], 'name' => $user['name']]);
            set_cookie('remember_token', $token30d, 30 * 24 * 3600);
        }

        return redirect()->to('/')->with('success', 'Welcome back, ' . $user['name'] . '!');
    }

    // ----------------------------------------------------------------
    // Logout
    // ----------------------------------------------------------------

    public function logout()
    {
        session()->destroy();
        delete_cookie('auth_token');
        delete_cookie('remember_token');
        return redirect()->to('/login')->with('success', 'You have been logged out.');
    }

    // ----------------------------------------------------------------
    // Private helper: set HttpOnly JWT cookie
    // ----------------------------------------------------------------

    private function setAuthCookie(string $token): void
    {
        // HttpOnly = JavaScript cannot read this cookie (security)
        $response = $this->response;
        $response->setCookie([
            'name'     => 'auth_token',
            'value'    => $token,
            'expire'   => (int)($_ENV['JWT_EXPIRE'] ?? 86400),
            'httponly' => true,
            'secure'   => false,  // set true in production (HTTPS)
            'samesite' => 'Lax',
        ]);
    }
}
