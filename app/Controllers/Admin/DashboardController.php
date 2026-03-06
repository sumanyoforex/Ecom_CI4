<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * Admin\DashboardController — shows summary stats on the admin homepage.
 */
class DashboardController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $stats = [
            'total_products' => $db->table('products')->countAll(),
            'total_orders'   => $db->table('orders')->countAll(),
            'total_users'    => $db->table('users')->countAll(),
            'total_revenue'  => $db->table('orders')->selectSum('total')->get()->getRowArray()['total'] ?? 0,
        ];

        // Last 5 orders for quick view
        $recentOrders = $db->table('orders o')
            ->select('o.id, o.total, o.status, o.created_at, u.name AS customer')
            ->join('users u', 'u.id = o.user_id', 'left')
            ->orderBy('o.created_at', 'DESC')
            ->limit(5)->get()->getResultArray();

        return view('admin/dashboard', [
            'stats'        => $stats,
            'recentOrders' => $recentOrders,
        ]);
    }
}
