<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Orders</h2>

<div class="card border-0 shadow-sm">
    <table class="table mb-0">
        <thead class="table-light">
            <tr><th>#</th><th>Customer</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th><th></th></tr>
        </thead>
        <tbody>
        <?php if (empty($orders)): ?>
            <tr><td colspan="7" class="text-center py-4 text-muted">No orders yet.</td></tr>
        <?php else: ?>
        <?php foreach ($orders as $o): ?>
        <tr>
            <td>#<?= $o['id'] ?></td>
            <td><?= esc($o['customer'] ?? 'Guest') ?></td>
            <td>$<?= number_format($o['total'],2) ?></td>
            <td><?= esc($o['payment_method']) ?></td>
            <td>
                <span class="badge bg-<?= match($o['status']) {
                    'delivered' => 'success',
                    'shipped'   => 'info',
                    'cancelled' => 'danger',
                    default     => 'warning'
                } ?>"><?= ucfirst($o['status']) ?></span>
            </td>
            <td><?= date('d M Y', strtotime($o['created_at'])) ?></td>
            <td><a href="<?= base_url('admin/orders/'.$o['id']) ?>" class="btn btn-sm btn-outline-primary">View</a></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
