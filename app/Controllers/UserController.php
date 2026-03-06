<?php

namespace App\Controllers;

use App\Models\OrderModel;

/**
 * UserController — customer account area.
 * Shows order history and individual order details.
 * Protected by AuthFilter.
 */
class UserController extends BaseController
{
    /** GET /account — order history */
    public function account()
    {
        $uid    = session()->get('user_id');
        $orders = (new OrderModel())->getOrdersByUser($uid);
        return view('shop/account', ['orders' => $orders]);
    }

    /** GET /order/{id} — single order detail */
    public function orderDetail(int $id)
    {
        $order = (new OrderModel())->getOrderWithItems($id);
        if (empty($order) || $order['user_id'] != session()->get('user_id')) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Order not found.');
        }
        return view('shop/order_detail', ['order' => $order]);
    }
}
