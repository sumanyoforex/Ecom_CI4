<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;

/**
 * Admin\OrderController — view and update order statuses.
 */
class OrderController extends BaseController
{
    private OrderModel $orders;

    public function __construct()
    {
        $this->orders = new OrderModel();
    }

    /** GET /admin/orders */
    public function index()
    {
        $orders = \Config\Database::connect()
            ->table('orders o')
            ->select('o.*, u.name AS customer')
            ->join('users u', 'u.id = o.user_id', 'left')
            ->orderBy('o.created_at', 'DESC')
            ->get()->getResultArray();

        return view('admin/orders/index', ['orders' => $orders]);
    }

    /** GET /admin/orders/{id} */
    public function detail(int $id)
    {
        $order = $this->orders->getOrderWithItems($id);
        return view('admin/orders/detail', ['order' => $order]);
    }

    /** POST /admin/orders/status/{id} — update order status */
    public function updateStatus(int $id)
    {
        $status = $this->request->getPost('status');
        $this->orders->update($id, ['status' => $status]);
        return redirect()->to('/admin/orders/' . $id)->with('success', 'Order status updated.');
    }
}
