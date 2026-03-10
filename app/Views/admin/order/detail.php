<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Order <?= esc($order['order_number'] ?? ('#' . $order['id'])) ?></h2>
    <a href="<?= base_url('admin/orders') ?>" class="btn btn-outline-secondary">Back</a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-3 mb-3">
            <h6 class="fw-bold mb-3">Order Info</h6>
            <p class="mb-1">Status: <strong><?= esc(ucfirst((string)$order['status'])) ?></strong></p>
            <p class="mb-1">Payment: <strong><?= esc((string)$order['payment_method']) ?></strong></p>
            <p class="mb-1">Payment Status: <strong><?= esc((string)($order['payment_status'] ?? 'pending')) ?></strong></p>
            <p class="mb-1">Coupon: <strong><?= esc((string)($order['coupon_code'] ?? '-')) ?></strong></p>
            <p class="mb-1">Address: <span class="text-muted"><?= esc((string)$order['shipping_address']) ?></span></p>
            <hr>
            <p class="mb-1">Subtotal: <strong>$<?= number_format((float)($order['subtotal'] ?? $order['total']), 2) ?></strong></p>
            <p class="mb-1">Discount: <strong>$<?= number_format((float)($order['discount_total'] ?? 0), 2) ?></strong></p>
            <p class="mb-1">Shipping: <strong>$<?= number_format((float)($order['shipping_total'] ?? 0), 2) ?></strong></p>
            <p class="mb-1">Tax: <strong>$<?= number_format((float)($order['tax_total'] ?? 0), 2) ?></strong></p>
            <p class="mb-0">Total: <strong>$<?= number_format((float)$order['total'], 2) ?></strong></p>
        </div>

        <div class="card border-0 shadow-sm p-3 mb-3">
            <h6 class="fw-bold mb-3">Update Status</h6>
            <form method="post" action="<?= base_url('admin/orders/status/' . $order['id']) ?>">
                <?= csrf_field() ?>
                <select name="status" class="form-select mb-2">
                    <?php foreach (['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'] as $s): ?>
                        <option value="<?= $s ?>" <?= $order['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                    <?php endforeach; ?>
                </select>
                <textarea name="note" class="form-control mb-2" rows="2" placeholder="Optional note..."></textarea>
                <button class="btn btn-primary w-100" type="submit">Update Status</button>
            </form>
        </div>

        <?php if (!empty($order['status_logs'])): ?>
            <div class="card border-0 shadow-sm p-3">
                <h6 class="fw-bold mb-3">Status History</h6>
                <?php foreach ($order['status_logs'] as $log): ?>
                    <div class="small border-bottom pb-2 mb-2">
                        <strong><?= esc(ucfirst((string)$log['to_status'])) ?></strong>
                        <div class="text-muted"><?= date('d M Y H:i', strtotime((string)$log['created_at'])) ?></div>
                        <?php if (!empty($log['note'])): ?><div><?= esc((string)$log['note']) ?></div><?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">Items</div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light"><tr><th>Product</th><th class="text-center">Qty</th><th class="text-end">Price</th><th class="text-end">Subtotal</th></tr></thead>
                    <tbody>
                    <?php foreach ($order['items'] as $item): ?>
                        <tr>
                            <td>
                                <img loading="lazy" decoding="async" src="<?= esc($item['image_url']) ?>" width="50" class="rounded me-2" alt="<?= esc($item['name']) ?>">
                                <?= esc($item['name']) ?>
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

