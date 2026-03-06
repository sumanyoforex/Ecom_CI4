<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Order #<?= $order['id'] ?></h2>
    <a href="<?= base_url('admin/orders') ?>" class="btn btn-outline-secondary">← Back</a>
</div>

<div class="row g-4">
    <!-- Order Info -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3 mb-3">
            <h6 class="fw-bold">Order Info</h6>
            <p class="mb-1">Total: <strong>$<?= number_format($order['total'],2) ?></strong></p>
            <p class="mb-1">Payment: <strong><?= esc($order['payment_method']) ?></strong></p>
            <p class="mb-1">Address: <span class="text-muted"><?= esc($order['shipping_address']) ?></span></p>
        </div>

        <!-- Update Status -->
        <div class="card border-0 shadow-sm p-3">
            <h6 class="fw-bold">Update Status</h6>
            <form method="post" action="<?= base_url('admin/orders/status/'.$order['id']) ?>">
                <?= csrf_field() ?>
                <select name="status" class="form-select mb-2">
                    <?php foreach (['pending','processing','shipped','delivered','cancelled'] as $s): ?>
                        <option value="<?= $s ?>" <?= $order['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="btn btn-primary w-100">Update</button>
            </form>
        </div>
    </div>

    <!-- Order Items -->
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
