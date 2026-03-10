<?php

namespace App\Controllers;

use App\Libraries\NotificationService;
use App\Libraries\PaymentGateway;
use App\Models\CartModel;
use App\Models\CouponModel;
use App\Models\OrderModel;
use App\Models\PaymentModel;

/**
 * Checkout controller with coupon support and stock-safe transaction flow.
 */
class CheckoutController extends BaseController
{
    public function index()
    {
        $uid = (int)session()->get('user_id');
        $sid = session_id();
        $items = (new CartModel())->getCartItems($sid, $uid);

        if (empty($items)) {
            return redirect()->to('/cart')->with('error', 'Your cart is empty.');
        }

        $summary = $this->buildCheckoutSummary($items);

        return view('checkout/index', [
            'items' => $items,
            'summary' => $summary,
            'appliedCouponCode' => session()->get('coupon_code'),
        ]);
    }

    public function applyCoupon()
    {
        $uid = (int)session()->get('user_id');
        $sid = session_id();
        $items = (new CartModel())->getCartItems($sid, $uid);

        if (empty($items)) {
            return redirect()->to('/cart')->with('error', 'Add items to your cart before applying a coupon.');
        }

        $code = strtoupper(trim((string)$this->request->getPost('coupon_code')));
        if ($code === '') {
            return redirect()->back()->with('error', 'Please enter a coupon code.');
        }

        $summary = $this->buildCheckoutSummary($items, $code);
        if (empty($summary['coupon'])) {
            return redirect()->back()->with('error', 'Coupon is invalid or not applicable for this order.');
        }

        session()->set('coupon_code', $summary['coupon']['code']);

        return redirect()->to('/checkout')->with('success', 'Coupon applied successfully.');
    }

    public function removeCoupon()
    {
        session()->remove('coupon_code');
        return redirect()->to('/checkout')->with('success', 'Coupon removed.');
    }

    public function place()
    {
        $uid = (int)session()->get('user_id');
        $sid = session_id();

        $address = trim((string)$this->request->getPost('address'));
        $paymentMethod = strtoupper(trim((string)$this->request->getPost('payment')));
        $paymentMethod = in_array($paymentMethod, ['COD', 'CARD', 'UPI'], true) ? $paymentMethod : 'COD';

        if (mb_strlen($address) < 12) {
            return redirect()->back()->withInput()->with('error', 'Please enter a complete shipping address.');
        }

        $cartModel = new CartModel();
        $items = $cartModel->getCartItems($sid, $uid);

        if (empty($items)) {
            return redirect()->to('/cart')->with('error', 'Your cart is empty.');
        }

        $summary = $this->buildCheckoutSummary($items);
        $coupon = $summary['coupon'];

        $db = \Config\Database::connect();
        $orderModel = new OrderModel();
        $paymentModel = new PaymentModel();
        $gateway = new PaymentGateway();

        $db->transBegin();

        try {
            foreach ($items as $item) {
                $row = $db->table('products')
                    ->select('id, stock, status')
                    ->where('id', (int)$item['product_id'])
                    ->get()
                    ->getRowArray();

                if (!$row || $row['status'] !== 'active' || (int)$row['stock'] < (int)$item['qty']) {
                    throw new \RuntimeException('One or more items are out of stock. Please update your cart.');
                }
            }

            $orderNumber = $orderModel->generateOrderNumber();

            $orderId = $orderModel->insert([
                'user_id' => $uid,
                'order_number' => $orderNumber,
                'subtotal' => $summary['subtotal'],
                'discount_total' => $summary['discount'],
                'shipping_total' => $summary['shipping'],
                'tax_total' => $summary['tax'],
                'total' => $summary['grand_total'],
                'status' => 'pending',
                'shipping_address' => $address,
                'payment_method' => $paymentMethod,
                'coupon_code' => $coupon['code'] ?? null,
                'payment_status' => $paymentMethod === 'COD' ? 'pending' : 'unpaid',
            ], true);

            if (!$orderId) {
                throw new \RuntimeException('Order could not be created. Please try again.');
            }

            foreach ($items as $item) {
                $db->table('order_items')->insert([
                    'order_id' => $orderId,
                    'product_id' => (int)$item['product_id'],
                    'qty' => (int)$item['qty'],
                    'price' => (float)$item['unit_price'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                $db->table('products')
                    ->set('stock', 'stock - ' . (int)$item['qty'], false)
                    ->where('id', (int)$item['product_id'])
                    ->where('stock >=', (int)$item['qty'])
                    ->update();

                if ($db->affectedRows() === 0) {
                    throw new \RuntimeException('Stock changed while placing your order. Please retry.');
                }
            }

            if (!empty($coupon['code'])) {
                $db->table('coupons')
                    ->set('used_count', 'used_count + 1', false)
                    ->where('code', $coupon['code'])
                    ->update();
            }

            $paymentMessage = '';
            if ($paymentMethod === 'COD') {
                $paymentModel->insert([
                    'order_id' => $orderId,
                    'provider' => 'cod',
                    'provider_payment_id' => null,
                    'amount' => $summary['grand_total'],
                    'currency' => (string)env('PAYMENT_CURRENCY', 'USD'),
                    'status' => 'pending',
                    'payment_url' => null,
                    'raw_payload' => json_encode(['method' => 'COD']),
                ]);
            } else {
                $intent = $gateway->createPaymentIntent($orderNumber, (float)$summary['grand_total']);

                $paymentModel->insert([
                    'order_id' => $orderId,
                    'provider' => $intent['provider'],
                    'provider_payment_id' => $intent['provider_payment_id'],
                    'amount' => $intent['amount'],
                    'currency' => $intent['currency'],
                    'status' => 'initiated',
                    'payment_url' => $intent['payment_url'],
                    'raw_payload' => json_encode($intent),
                ]);

                $paymentMessage = ' Payment is pending. Use the "Complete Payment" action in your account (mock flow) or integrate your live gateway webhook.';
            }

            $db->table('order_status_logs')->insert([
                'order_id' => $orderId,
                'from_status' => null,
                'to_status' => 'pending',
                'note' => 'Order placed by customer',
                'changed_by' => (string)session()->get('user_name'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            $cartModel->clearCart($sid, $uid);
            session()->remove('coupon_code');

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Unable to complete your order right now.');
            }

            $db->transCommit();

            $recipient = $db->table('users')->select('email')->where('id', $uid)->get()->getRowArray();
            if (!empty($recipient['email'])) {
                (new NotificationService())->sendEmail(
                    $uid,
                    (string)$recipient['email'],
                    'Order confirmation - ' . $orderNumber,
                    'Thanks for shopping with us. Your order total is $' . number_format((float)$summary['grand_total'], 2) . '.'
                );
            }

            return redirect()->to('/account')->with('success', 'Order ' . $orderNumber . ' placed successfully.' . $paymentMessage);
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Checkout failed: {message}', ['message' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    private function buildCheckoutSummary(array $items, ?string $forcedCouponCode = null): array
    {
        $subtotal = round((float)array_sum(array_column($items, 'subtotal')), 2);
        $couponCode = $forcedCouponCode ?? (string)session()->get('coupon_code');

        $coupon = null;
        $discount = 0.0;

        if ($couponCode !== '') {
            $couponModel = new CouponModel();
            $candidate = $couponModel->findValidCoupon($couponCode);
            if ($candidate) {
                $candidateDiscount = $couponModel->calculateDiscount($candidate, $subtotal);
                if ($candidateDiscount > 0) {
                    $coupon = $candidate;
                    $discount = $candidateDiscount;
                }
            }
        }

        $discountedSubtotal = max(0.0, $subtotal - $discount);
        $shipping = $discountedSubtotal >= 120 ? 0.0 : 7.0;
        $tax = round($discountedSubtotal * 0.05, 2);
        $grandTotal = round($discountedSubtotal + $shipping + $tax, 2);

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping' => $shipping,
            'tax' => $tax,
            'grand_total' => $grandTotal,
            'coupon' => $coupon,
        ];
    }
}

