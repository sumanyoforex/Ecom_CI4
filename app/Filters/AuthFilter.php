<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Protects routes that require an authenticated customer session.
 */
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if ($session->get('user_id')) {
            return;
        }

        $token = $request->getCookie('auth_token') ?: $request->getCookie('remember_token');
        if ($token) {
            $payload = jwt_verify($token);
            if ($payload && isset($payload->user_id)) {
                $session->set('user_id', (int)$payload->user_id);
                $session->set('user_name', (string)($payload->name ?? 'Customer'));
                return;
            }
        }

        return redirect()->to('/login')->with('error', 'Please login to continue.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
