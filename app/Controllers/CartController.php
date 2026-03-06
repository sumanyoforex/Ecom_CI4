<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\ProductModel;

/**
 * CartController — add, view, update, and remove cart items.
 *
 * Cart key = session_id for guests, user_id for logged-in users.
 */
class CartController extends BaseController
{
    private function getCartKey(): array
    {
        $sessionId = session_id();
        $userId    = session()->get('user_id') ?? 0;
        return [$sessionId, (int)$userId];
    }

    /** GET /cart — show cart page */
    public function index()
    {
        [$sid, $uid] = $this->getCartKey();
        $items = (new CartModel())->getCartItems($sid, $uid);
        $total = array_sum(array_column($items, 'subtotal'));

        return view('shop/cart', ['items' => $items, 'total' => $total]);
    }

    /** POST /cart/add — add a product to cart */
    public function add()
    {
        $productId = (int)$this->request->getPost('product_id');
        $qty       = max(1, (int)$this->request->getPost('qty'));

        [$sid, $uid] = $this->getCartKey();
        $cartModel   = new CartModel();

        // Check if this product is already in the cart
        $existing = $cartModel
            ->where('product_id', $productId)
            ->where($uid > 0 ? 'user_id' : 'session_id', $uid > 0 ? $uid : $sid)
            ->first();

        if ($existing) {
            // Increase quantity
            $cartModel->update($existing['id'], ['qty' => $existing['qty'] + $qty]);
        } else {
            // Add new row
            $cartModel->insert([
                'session_id' => $sid,
                'user_id'    => $uid > 0 ? $uid : null,
                'product_id' => $productId,
                'qty'        => $qty,
            ]);
        }

        return redirect()->to('/cart')->with('success', 'Item added to cart.');
    }

    /** POST /cart/update — change qty of a cart item */
    public function update()
    {
        $id  = (int)$this->request->getPost('cart_id');
        $qty = max(1, (int)$this->request->getPost('qty'));
        (new CartModel())->update($id, ['qty' => $qty]);
        return redirect()->to('/cart');
    }

    /** POST /cart/remove — remove an item from cart */
    public function remove()
    {
        $id = (int)$this->request->getPost('cart_id');
        (new CartModel())->delete($id);
        return redirect()->to('/cart')->with('success', 'Item removed.');
    }
}
