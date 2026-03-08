<?php

namespace App\Controllers;

use App\Libraries\NotificationService;
use App\Models\OrderModel;
use App\Models\ProductRatingModel;

/**
 * Customer account and order history actions.
 */
class UserController extends BaseController
{
    public function account()
    {
        $uid = (int)session()->get('user_id');
        $orders = (new OrderModel())->getOrdersByUser($uid);
        return view('shop/account', ['orders' => $orders]);
    }

    public function orderDetail(int $id)
    {
        $uid = (int)session()->get('user_id');
        $order = (new OrderModel())->getOrderWithItems($id);
        if (empty($order) || (int)$order['user_id'] !== $uid) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Order not found.');
        }

        $ratings = (new ProductRatingModel())->getOrderRatingsByUser($id, $uid);
        $ratingsByProduct = [];
        foreach ($ratings as $r) {
            $ratingsByProduct[(int)$r['product_id']] = $r;
        }

        return view('shop/order_detail', [
            'order' => $order,
            'ratingsByProduct' => $ratingsByProduct,
        ]);
    }

    public function rateOrderItem(int $id)
    {
        $uid = (int)session()->get('user_id');
        $order = (new OrderModel())->find($id);

        if (!$order || (int)$order['user_id'] !== $uid) {
            return redirect()->to('/account')->with('error', 'Order not found.');
        }

        if (strtolower((string)$order['status']) !== 'delivered') {
            return redirect()->to('/order/' . $id)->with('error', 'You can rate products only after delivery.');
        }

        $rules = [
            'product_id' => 'required|integer',
            'rating' => 'required|in_list[1,2,3,4,5]',
            'review' => 'permit_empty|max_length[1000]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/order/' . $id)->with('error', 'Please provide a valid rating (1 to 5).');
        }

        $productId = (int)$this->request->getPost('product_id');
        $rating = (int)$this->request->getPost('rating');
        $review = trim((string)$this->request->getPost('review'));

        $itemExists = \Config\Database::connect()->table('order_items')
            ->where('order_id', $id)
            ->where('product_id', $productId)
            ->countAllResults() > 0;

        if (!$itemExists) {
            return redirect()->to('/order/' . $id)->with('error', 'Invalid product for this order.');
        }

        (new ProductRatingModel())->upsertRating($uid, $id, $productId, $rating, $review);

        return redirect()->to('/order/' . $id)->with('success', 'Thanks! Your rating has been saved.');
    }

    public function cancelOrder(int $id)
    {
        $uid = (int)session()->get('user_id');
        $orderModel = new OrderModel();
        $order = $orderModel->find($id);

        if (!$order || (int)$order['user_id'] !== $uid) {
            return redirect()->to('/account')->with('error', 'Order not found.');
        }

        if (!in_array($order['status'], ['pending', 'confirmed', 'processing'], true)) {
            return redirect()->to('/order/' . $id)->with('error', 'This order can no longer be cancelled.');
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $items = $db->table('order_items')->where('order_id', $id)->get()->getResultArray();
            foreach ($items as $item) {
                $db->table('products')
                    ->set('stock', 'stock + ' . (int)$item['qty'], false)
                    ->where('id', (int)$item['product_id'])
                    ->update();
            }

            $orderModel->update($id, ['status' => 'cancelled']);

            $db->table('order_status_logs')->insert([
                'order_id' => $id,
                'from_status' => $order['status'],
                'to_status' => 'cancelled',
                'note' => 'Cancelled by customer',
                'changed_by' => (string)session()->get('user_name'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Unable to cancel order.');
            }

            $db->transCommit();

            $user = $db->table('users')->select('id, email')->where('id', $uid)->get()->getRowArray();
            if (!empty($user['email'])) {
                (new NotificationService())->sendEmail(
                    (int)$user['id'],
                    (string)$user['email'],
                    'Order cancelled - ' . ($order['order_number'] ?: ('#' . $order['id'])),
                    'Your order has been cancelled successfully.'
                );
            }

            return redirect()->to('/order/' . $id)->with('success', 'Order cancelled successfully.');
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->to('/order/' . $id)->with('error', 'Could not cancel order at this time.');
        }
    }
}
