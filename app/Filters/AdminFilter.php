<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * AdminFilter — Protects all /admin/* routes.
 *
 * Only passes through if the session has 'is_admin' = true.
 * This flag is set in Admin\AuthController after successful login.
 */
class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check the admin session flag
        if (session()->get('is_admin') !== true) {
            return redirect()->to('/admin/login')
                             ->with('error', 'Admin access required.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing needed after the response
    }
}
