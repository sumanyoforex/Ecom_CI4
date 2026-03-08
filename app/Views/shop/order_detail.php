<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Order Detail</h2>
    <a href="<?= base_url('account') ?>" class="btn btn-outline-secondary">Back to My Orders</a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 p-3 mb-3">
            <h6 class="fw-bold mb-3">Summary</h6>
            <div class="small text-muted">Order Number</div>
            <div class="fw-semibold mb-2"><?= esc($order['order_number'] ?? ('#' . $order['id'])) ?></div>
            <div class="small text-muted">Status</div>
            <div class="mb-2"><span class="badge bg-secondary"><?= esc(ucfirst((string)$order['status'])) ?></span></div>
            <div class="small text-muted">Payment</div>
            <div class="mb-2"><?= esc((string)$order['payment_method']) ?> (<?= esc((string)($order['payment_status'] ?? 'pending')) ?>)</div>
            <div class="small text-muted">Shipping Address</div>
            <div class="mb-3"><?= esc((string)$order['shipping_address']) ?></div>

            <hr>
            <div class="d-flex justify-content-between"><span>Subtotal</span><strong>$<?= number_format((float)($order['subtotal'] ?? $order['total']), 2) ?></strong></div>
            <div class="d-flex justify-content-between"><span>Discount</span><strong class="text-success">-$<?= number_format((float)($order['discount_total'] ?? 0), 2) ?></strong></div>
            <div class="d-flex justify-content-between"><span>Shipping</span><strong>$<?= number_format((float)($order['shipping_total'] ?? 0), 2) ?></strong></div>
            <div class="d-flex justify-content-between"><span>Tax</span><strong>$<?= number_format((float)($order['tax_total'] ?? 0), 2) ?></strong></div>
            <div class="d-flex justify-content-between mt-2 fs-5"><span>Total</span><strong>$<?= number_format((float)$order['total'], 2) ?></strong></div>

            <?php
                $canPay = in_array(strtolower((string)($order['payment_status'] ?? '')), ['unpaid', 'pending'], true)
                    && in_array(strtoupper((string)$order['payment_method']), ['CARD', 'UPI'], true)
                    && in_array((string)$order['status'], ['pending', 'confirmed'], true);
            ?>
            <?php if ($canPay): ?>
                <form method="post" action="<?= base_url('payment/mock-success/' . $order['id']) ?>" class="mt-3">
                    <?= csrf_field() ?>
                    <button class="btn btn-brand w-100" type="submit">Complete Payment (Mock)</button>
                </form>
            <?php endif; ?>

            <?php if (in_array((string)$order['status'], ['pending', 'confirmed', 'processing'], true)): ?>
                <form method="post" action="<?= base_url('order/cancel/' . $order['id']) ?>" class="mt-2">
                    <?= csrf_field() ?>
                    <button class="btn btn-outline-danger w-100" type="submit">Cancel Order</button>
                </form>
            <?php endif; ?>
        </div>

        <?php if (!empty($order['status_logs'])): ?>
            <div class="card border-0 p-3">
                <h6 class="fw-bold mb-3">Status History</h6>
                <?php foreach ($order['status_logs'] as $log): ?>
                    <div class="small mb-2 pb-2 border-bottom">
                        <strong><?= esc(ucfirst((string)$log['to_status'])) ?></strong>
                        <div class="text-muted"><?= date('d M Y H:i', strtotime((string)$log['created_at'])) ?></div>
                        <?php if (!empty($log['note'])): ?><div><?= esc($log['note']) ?></div><?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 p-2 p-md-3">
            <h5 class="mb-3">Items</h5>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                    <tr><th>Product</th><th class="text-center">Qty</th><th class="text-end">Price</th><th class="text-end">Subtotal</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($order['items'] as $item): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img loading="lazy" decoding="async" src="<?= esc($item['image_url']) ?>" width="52" class="rounded" alt="<?= esc($item['name']) ?>">
                                    <span><?= esc($item['name']) ?></span>
                                </div>
                            </td>
                            <td class="text-center"><?= (int)$item['qty'] ?></td>
                            <td class="text-end">$<?= number_format((float)$item['price'], 2) ?></td>
                            <td class="text-end">$<?= number_format((float)$item['price'] * (int)$item['qty'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

