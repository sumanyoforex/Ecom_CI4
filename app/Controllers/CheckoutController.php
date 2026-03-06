<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\OrderModel;

/**
 * CheckoutController — lets logged-in users place an order.
 *
 * Protected by AuthFilter (see Routes.php + Filters.php).
 */
class CheckoutController extends BaseController
{
    /** GET /checkout — show checkout form */
    public function index()
    {
        $uid   = session()->get('user_id');
        $sid   = session_id();
        $items = (new CartModel())->getCartItems($sid, $uid);

        if (empty($items)) {
            return redirect()->to('/cart')->with('error', 'Your cart is empty.');
        }

        $total = array_sum(array_column($items, 'subtotal'));
        return view('shop/checkout', ['items' => $items, 'total' => $total]);
    }

    /** POST /checkout/place — create order from cart */
    public function place()
    {
        $uid     = session()->get('user_id');
        $sid     = session_id();
        $items   = (new CartModel())->getCartItems($sid, $uid);
        $total   = array_sum(array_column($items, 'subtotal'));

        // Validate shipping address
        if (!$this->request->getPost('address')) {
            return redirect()->back()->with('error', 'Shipping address is required.');
        }

        $orderModel = new OrderModel();

        // Create the order header
        $orderId = $orderModel->insert([
            'user_id'          => $uid,
            'total'            => $total,
            'status'           => 'pending',
            'shipping_address' => $this->request->getPost('address'),
            'payment_method'   => $this->request->getPost('payment') ?? 'COD',
        ]);

        // Insert each cart item as an order line
        $db = \Config\Database::connect();
        foreach ($items as $item) {
            $db->table('order_items')->insert([
                'order_id'   => $orderId,
                'product_id' => $this->getProductIdByName($item['name']),
                'qty'        => $item['qty'],
                'price'      => $item['unit_price'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        // Clear the cart
        (new CartModel())->clearCart($sid, $uid);

        return redirect()->to('/account')->with('success', "Order #$orderId placed! Thank you.");
    }

    private function getProductIdByName(string $name): int
    {
        $row = \Config\Database::connect()
            ->table('products')->select('id')->where('name', $name)->get()->getRowArray();
        return $row['id'] ?? 0;
    }
}
