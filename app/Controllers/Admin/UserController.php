<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

/**
 * Admin\UserController — read-only view of registered customers.
 */
class UserController extends BaseController
{
    /** GET /admin/users */
    public function index()
    {
        return view('admin/users/index', [
            'users' => (new UserModel())->orderBy('created_at', 'DESC')->findAll(),
        ]);
    }
}
