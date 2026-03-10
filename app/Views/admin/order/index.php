<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Orders</h2>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
            <tr>
                <th>Order</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Date</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($orders)): ?>
                <tr><td colspan="7" class="text-center py-4 text-muted">No orders yet.</td></tr>
            <?php else: ?>
                <?php foreach ($orders as $o): ?>
                    <tr>
                        <td>
                            <div class="fw-semibold"><?= esc($o['order_number'] ?? ('#' . $o['id'])) ?></div>
                            <small class="text-muted">ID: <?= (int)$o['id'] ?></small>
                        </td>
                        <td><?= esc($o['customer'] ?? 'Guest') ?></td>
                        <td>$<?= number_format((float)$o['total'], 2) ?></td>
                        <td>
                            <div><?= esc((string)$o['payment_method']) ?></div>
                            <small class="text-muted"><?= esc((string)($o['payment_status'] ?? 'pending')) ?></small>
                        </td>
                        <td><span class="badge bg-secondary"><?= esc(ucfirst((string)$o['status'])) ?></span></td>
                        <td><?= date('d M Y', strtotime((string)$o['created_at'])) ?></td>
                        <td><a href="<?= base_url('admin/orders/' . $o['id']) ?>" class="btn btn-sm btn-outline-primary">View</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
