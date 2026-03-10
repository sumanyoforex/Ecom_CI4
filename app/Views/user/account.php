<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">My Orders</h2>

<?php if (empty($orders)): ?>
    <div class="alert alert-info">You have no orders yet. <a href="<?= base_url('shop') ?>">Start shopping</a>.</div>
<?php else: ?>
    <div class="card border-0 p-2 p-md-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                <tr>
                    <th>Order</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($orders as $order): ?>
                    <?php
                        $status = strtolower((string)$order['status']);
                        $badge = match ($status) {
                            'delivered' => 'success',
                            'shipped' => 'info',
                            'cancelled', 'refunded' => 'danger',
                            'confirmed', 'processing' => 'primary',
                            default => 'warning',
                        };
                        $canPay = in_array(strtolower((string)($order['payment_status'] ?? '')), ['unpaid', 'pending'], true)
                            && in_array($status, ['pending', 'confirmed'], true)
                            && in_array(strtoupper((string)$order['payment_method']), ['CARD', 'UPI'], true);
                    ?>
                    <tr>
                        <td>
                            <div class="fw-semibold"><?= esc($order['order_number'] ?? ('#' . $order['id'])) ?></div>
                            <small class="text-muted">ID: <?= (int)$order['id'] ?></small>
                        </td>
                        <td><?= date('d M Y', strtotime((string)$order['created_at'])) ?></td>
                        <td>$<?= number_format((float)$order['total'], 2) ?></td>
                        <td><?= esc(ucfirst((string)($order['payment_status'] ?? 'pending'))) ?></td>
                        <td><span class="badge bg-<?= $badge ?>"><?= esc(ucfirst($status)) ?></span></td>
                        <td class="text-nowrap">
                            <a href="<?= base_url('order/' . $order['id']) ?>" class="btn btn-sm btn-outline-primary">Details</a>
                            <?php if ($canPay): ?>
                                <form method="post" action="<?= base_url('payment/mock-success/' . $order['id']) ?>" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-sm btn-brand" type="submit">Complete Payment</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
