<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * AuthFilter — Protects routes that require a logged-in user.
 *
 * Checks in order:
 *   1. Session:  $_SESSION['user_id'] is set
 *   2. Cookie:   'auth_token' HttpOnly cookie (JWT)
 *
 * If neither exists → redirect to /login.
 */
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Case 1: Active session — user is already logged in
        if ($session->get('user_id')) {
            return; // allow through
        }

        // Case 2: JWT cookie — remember-me / API token
        $token = $request->getCookie('auth_token');
        if ($token) {
            $payload = jwt_verify($token);
            if ($payload) {
                // Restore session from JWT so future checks are fast
                $session->set('user_id', $payload->user_id);
                $session->set('user_name', $payload->name);
                return;
            }
        }

        // Neither — redirect to login
        return redirect()->to('/login')->with('error', 'Please login to continue.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing needed after the response
    }
}
