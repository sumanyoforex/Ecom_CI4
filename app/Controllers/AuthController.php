<?php

namespace App\Controllers;

use App\Libraries\NotificationService;
use App\Models\PasswordResetModel;
use App\Models\UserModel;

/**
 * AuthController handles customer registration/login/logout and password reset.
 */
class AuthController extends BaseController
{
    public function registerForm()
    {
        return view('auth/register');
    }

    public function register()
    {
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]|max_length[72]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/]',
        ];

        if (!$this->validate($rules)) {
            return view('auth/register', ['errors' => $this->validator->getErrors()]);
        }

        $model = new UserModel();
        $userId = $model->insert([
            'name' => trim((string)$this->request->getPost('name')),
            'email' => strtolower(trim((string)$this->request->getPost('email'))),
            'password' => (string)$this->request->getPost('password'),
        ]);

        if (!$userId) {
            return redirect()->back()->withInput()->with('error', 'Could not create account. Please try again.');
        }

        session()->regenerate(true);
        session()->set([
            'user_id' => $userId,
            'user_name' => (string)$this->request->getPost('name'),
        ]);

        $token = jwt_generate(['user_id' => $userId, 'name' => (string)$this->request->getPost('name')]);
        $this->setAuthCookie($token, (int)(env('JWT_EXPIRE', 86400)));

        return redirect()->to('/')->with('success', 'Welcome! Account created.');
    }

    public function loginForm()
    {
        return view('auth/login');
    }

    public function login()
    {
        $email = strtolower(trim((string)$this->request->getPost('email')));
        $password = (string)$this->request->getPost('password');

        if (!$this->validate([
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]|max_length[72]',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Please provide valid login details.');
        }

        $throttler = service('throttler');
        $attemptKey = 'login_' . sha1($this->request->getIPAddress() . '|' . $email);
        if (!$throttler->check($attemptKey, 7, 600)) {
            return redirect()->back()->withInput()->with('error', 'Too many login attempts. Please wait 10 minutes.');
        }

        // Admin credentials check first.
        if ($email === strtolower((string)env('ADMIN_EMAIL')) && $password === (string)env('ADMIN_PASSWORD')) {
            session()->regenerate(true);
            session()->set([
                'is_admin' => true,
                'admin_name' => 'Admin',
            ]);
            return redirect()->to('/admin');
        }

        $model = new UserModel();
        $user = $model->findByEmail($email);

        if (!$user || !password_verify($password, (string)$user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }

        session()->regenerate(true);
        session()->set([
            'user_id' => (int)$user['id'],
            'user_name' => (string)$user['name'],
        ]);

        $token = jwt_generate(['user_id' => (int)$user['id'], 'name' => (string)$user['name']]);
        $defaultExpiry = (int)env('JWT_EXPIRE', 86400);
        $rememberExpiry = 30 * 24 * 3600;

        $this->setAuthCookie($token, $defaultExpiry);

        if ($this->request->getPost('remember')) {
            $this->setRememberCookie($token, $rememberExpiry);
        }

        return redirect()->to('/')->with('success', 'Welcome back, ' . $user['name'] . '!');
    }

    public function forgotPasswordForm()
    {
        return view('auth/forgot_password');
    }

    public function sendPasswordReset()
    {
        $email = strtolower(trim((string)$this->request->getPost('email')));

        if (!$this->validate(['email' => 'required|valid_email'])) {
            return redirect()->back()->withInput()->with('error', 'Please enter a valid email address.');
        }

        $throttler = service('throttler');
        $attemptKey = 'forgot_' . sha1($this->request->getIPAddress() . '|' . $email);
        if (!$throttler->check($attemptKey, 5, 900)) {
            return redirect()->back()->with('error', 'Too many requests. Please try again in 15 minutes.');
        }

        // Admin password reset is intentionally not supported.
        if ($email === strtolower((string)env('ADMIN_EMAIL'))) {
            return redirect()->to('/forgot-password')->with('success', 'If an account exists, a reset link has been sent.');
        }

        $user = (new UserModel())->findByEmail($email);
        if ($user && strtolower((string)($user['role'] ?? 'customer')) !== 'admin') {
            $resets = new PasswordResetModel();
            $resets->where('user_id', (int)$user['id'])->where('used_at', null)->delete();

            $token = bin2hex(random_bytes(32));
            $resets->insert([
                'user_id' => (int)$user['id'],
                'email' => $email,
                'token_hash' => hash('sha256', $token),
                'expires_at' => date('Y-m-d H:i:s', strtotime('+30 minutes')),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            $link = base_url('reset-password/' . $token);
            (new NotificationService())->sendEmail(
                (int)$user['id'],
                $email,
                'Reset your ShopCI4 password',
                'Use this link to reset your password: <a href="' . $link . '">' . $link . '</a>. This link expires in 30 minutes.'
            );

            if (ENVIRONMENT !== 'production') {
                return redirect()->to('/forgot-password')
                    ->with('success', 'If an account exists, a reset link has been sent.')
                    ->with('dev_reset_link', $link);
            }
        }

        return redirect()->to('/forgot-password')->with('success', 'If an account exists, a reset link has been sent.');
    }

    public function resetPasswordForm(string $token)
    {
        $reset = (new PasswordResetModel())->findValidByToken($token);
        if (!$reset) {
            return redirect()->to('/forgot-password')->with('error', 'Reset link is invalid or expired.');
        }

        return view('auth/reset_password', ['token' => $token]);
    }

    public function resetPassword(string $token)
    {
        $resetModel = new PasswordResetModel();
        $reset = $resetModel->findValidByToken($token);
        if (!$reset) {
            return redirect()->to('/forgot-password')->with('error', 'Reset link is invalid or expired.');
        }

        $rules = [
            'password' => 'required|min_length[8]|max_length[72]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Please provide a strong matching password.');
        }

        $newPassword = (string)$this->request->getPost('password');
        (new UserModel())->update((int)$reset['user_id'], ['password' => $newPassword]);
        $resetModel->markUsed((int)$reset['id']);

        return redirect()->to('/login')->with('success', 'Password updated successfully. Please login.');
    }

    public function logout()
    {
        session()->destroy();
        $this->response->deleteCookie('auth_token');
        $this->response->deleteCookie('remember_token');

        return redirect()->to('/')->with('success', 'You have been logged out.');
    }

    private function setAuthCookie(string $token, int $expiry): void
    {
        $secure = $this->request->isSecure() || ENVIRONMENT === 'production';

        $this->response->setCookie([
            'name' => 'auth_token',
            'value' => $token,
            'expire' => $expiry,
            'httponly' => true,
            'secure' => $secure,
            'samesite' => 'Lax',
            'path' => '/',
        ]);
    }

    private function setRememberCookie(string $token, int $expiry): void
    {
        $secure = $this->request->isSecure() || ENVIRONMENT === 'production';

        $this->response->setCookie([
            'name' => 'remember_token',
            'value' => $token,
            'expire' => $expiry,
            'httponly' => true,
            'secure' => $secure,
            'samesite' => 'Lax',
            'path' => '/',
        ]);
    }
}

