<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">My Orders</h2>

<?php if (empty($orders)): ?>
    <div class="alert alert-info">You have no orders yet. <a href="<?= base_url('shop') ?>">Start shopping!</a></div>
<?php else: ?>
    <div class="card border-0 shadow-sm">
        <table class="table mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>Date</th><th>Total</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                <td>$<?= number_format($order['total'], 2) ?></td>
                <td>
                    <span class="badge bg-<?= match($order['status']) {
                        'delivered' => 'success',
                        'shipped'   => 'info',
                        'cancelled' => 'danger',
                        default     => 'warning'
                    } ?>"><?= ucfirst($order['status']) ?></span>
                </td>
                <td><a href="<?= base_url('order/'.$order['id']) ?>" class="btn btn-sm btn-outline-primary">Details</a></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
