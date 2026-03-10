<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\NotificationService;
use App\Models\OrderModel;

/**
 * Admin order operations with guarded status transitions.
 */
class OrderController extends BaseController
{
    private OrderModel $orders;

    public function __construct()
    {
        $this->orders = new OrderModel();
    }

    public function index()
    {
        $orders = \Config\Database::connect()
            ->table('orders o')
            ->select('o.*, u.name AS customer')
            ->join('users u', 'u.id = o.user_id', 'left')
            ->orderBy('o.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return view('admin/order/index', ['orders' => $orders]);
    }

    public function detail(int $id)
    {
        $order = $this->orders->getOrderWithItems($id);
        if (empty($order)) {
            return redirect()->to('/admin/orders')->with('error', 'Order not found.');
        }

        return view('admin/order/detail', ['order' => $order]);
    }

    public function updateStatus(int $id)
    {
        $target = strtolower(trim((string)$this->request->getPost('status')));
        $note = trim((string)$this->request->getPost('note'));

        $allowed = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'];
        if (!in_array($target, $allowed, true)) {
            return redirect()->back()->with('error', 'Invalid order status.');
        }

        $order = $this->orders->find($id);
        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Order not found.');
        }

        $current = (string)$order['status'];
        if ($current === $target) {
            return redirect()->back()->with('success', 'Order status already set.');
        }

        $transitionMap = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['processing', 'cancelled'],
            'processing' => ['shipped', 'cancelled'],
            'shipped' => ['delivered', 'refunded'],
            'delivered' => ['refunded'],
            'cancelled' => [],
            'refunded' => [],
        ];

        $validTargets = $transitionMap[$current] ?? [];
        if (!in_array($target, $validTargets, true)) {
            return redirect()->back()->with('error', 'Invalid transition from ' . $current . ' to ' . $target . '.');
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            if (in_array($target, ['cancelled', 'refunded'], true) && !in_array($current, ['cancelled', 'refunded'], true)) {
                $items = $db->table('order_items')->where('order_id', $id)->get()->getResultArray();
                foreach ($items as $item) {
                    $db->table('products')
                        ->set('stock', 'stock + ' . (int)$item['qty'], false)
                        ->where('id', (int)$item['product_id'])
                        ->update();
                }
            }

            $updateData = ['status' => $target];
            if ($target === 'delivered') {
                $updateData['payment_status'] = 'paid';
            }
            if ($target === 'refunded') {
                $updateData['payment_status'] = 'refunded';
            }

            $this->orders->update($id, $updateData);

            $db->table('order_status_logs')->insert([
                'order_id' => $id,
                'from_status' => $current,
                'to_status' => $target,
                'note' => $note !== '' ? $note : 'Updated from admin panel',
                'changed_by' => (string)session()->get('admin_name'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Unable to update order status.');
            }

            $db->transCommit();

            $user = $db->table('users')->select('id, email')->where('id', (int)$order['user_id'])->get()->getRowArray();
            if (!empty($user['email'])) {
                (new NotificationService())->sendEmail(
                    (int)$user['id'],
                    (string)$user['email'],
                    'Order status updated - ' . ($order['order_number'] ?: ('#' . $order['id'])),
                    'Your order status is now: ' . strtoupper($target)
                );
            }

            return redirect()->to('/admin/orders/' . $id)->with('success', 'Order status updated to ' . ucfirst($target) . '.');
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Admin order status update failed: {message}', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Could not update order status right now.');
        }
    }
}


