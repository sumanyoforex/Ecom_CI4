<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\ProductModel;

/**
 * Handles customer cart operations with stock validation.
 */
class CartController extends BaseController
{
    private function getCartKey(): array
    {
        return [session_id(), (int)(session()->get('user_id') ?? 0)];
    }

    private function getOwnedCartItem(int $cartId): ?array
    {
        [$sid, $uid] = $this->getCartKey();
        $builder = (new CartModel())->where('id', $cartId);

        if ($uid > 0) {
            $builder->where('user_id', $uid);
        } else {
            $builder->where('session_id', $sid)->where('user_id', null);
        }

        return $builder->first();
    }

    public function index()
    {
        [$sid, $uid] = $this->getCartKey();
        $items = (new CartModel())->getCartItems($sid, $uid);
        $total = array_sum(array_column($items, 'subtotal'));

        return view('shop/cart', ['items' => $items, 'total' => $total]);
    }

    public function add()
    {
        $productId = (int)$this->request->getPost('product_id');
        $qty = max(1, (int)$this->request->getPost('qty'));

        $product = (new ProductModel())->find($productId);
        if (!$product || $product['status'] !== 'active') {
            return redirect()->back()->with('error', 'This product is unavailable.');
        }

        if ($qty > (int)$product['stock']) {
            return redirect()->back()->with('error', 'Requested quantity is not available in stock.');
        }

        [$sid, $uid] = $this->getCartKey();
        $cartModel = new CartModel();

        $existing = $cartModel
            ->where('product_id', $productId)
            ->where($uid > 0 ? 'user_id' : 'session_id', $uid > 0 ? $uid : $sid)
            ->first();

        if ($existing) {
            $newQty = (int)$existing['qty'] + $qty;
            if ($newQty > (int)$product['stock']) {
                return redirect()->back()->with('error', 'Not enough stock for the requested quantity.');
            }

            $cartModel->update((int)$existing['id'], ['qty' => $newQty]);
        } else {
            $cartModel->insert([
                'session_id' => $sid,
                'user_id' => $uid > 0 ? $uid : null,
                'product_id' => $productId,
                'qty' => $qty,
            ]);
        }

        return redirect()->to('/cart')->with('success', 'Item added to cart.');
    }

    public function update()
    {
        $cartId = (int)$this->request->getPost('cart_id');
        $qty = max(1, (int)$this->request->getPost('qty'));

        $item = $this->getOwnedCartItem($cartId);
        if (!$item) {
            return redirect()->to('/cart')->with('error', 'Cart item not found.');
        }

        $product = (new ProductModel())->find((int)$item['product_id']);
        if (!$product || $qty > (int)$product['stock']) {
            return redirect()->to('/cart')->with('error', 'Not enough stock for the requested quantity.');
        }

        (new CartModel())->update($cartId, ['qty' => $qty]);

        return redirect()->to('/cart')->with('success', 'Cart updated.');
    }

    public function remove()
    {
        $cartId = (int)$this->request->getPost('cart_id');
        $item = $this->getOwnedCartItem($cartId);

        if (!$item) {
            return redirect()->to('/cart')->with('error', 'Cart item not found.');
        }

        (new CartModel())->delete($cartId);

        return redirect()->to('/cart')->with('success', 'Item removed.');
    }
}
