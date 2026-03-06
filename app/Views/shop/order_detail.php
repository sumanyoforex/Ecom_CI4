<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Order #<?= $order['id'] ?> Detail</h2>
    <a href="<?= base_url('account') ?>" class="btn btn-outline-secondary">← My Orders</a>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3">
            <h6 class="fw-bold">Order Summary</h6>
            <p>Status: <span class="badge bg-warning"><?= ucfirst($order['status']) ?></span></p>
            <p>Total: <strong>$<?= number_format($order['total'],2) ?></strong></p>
            <p>Payment: <?= esc($order['payment_method']) ?></p>
            <p>Ship to: <em class="text-muted"><?= esc($order['shipping_address']) ?></em></p>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">Items</div>
            <table class="table mb-0">
                <thead class="table-light"><tr><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead>
                <tbody>
                <?php foreach ($order['items'] as $item): ?>
                <tr>
                    <td>
                        <img src="<?= esc($item['image_url']) ?>" width="50" class="rounded me-2">
                        <?= esc($item['name']) ?>
                    </td>
                    <td><?= $item['qty'] ?></td>
                    <td>$<?= number_format($item['price'],2) ?></td>
                    <td>$<?= number_format($item['qty'] * $item['price'],2) ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
