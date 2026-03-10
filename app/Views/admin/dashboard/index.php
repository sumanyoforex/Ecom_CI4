<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Dashboard</h2>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card text-center p-3">
            <div class="fs-1 text-primary mb-1"><i class="fa fa-box"></i></div>
            <h3 class="fw-bold"><?= $stats['total_products'] ?></h3>
            <p class="text-muted mb-0">Products</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-center p-3">
            <div class="fs-1 text-warning mb-1"><i class="fa fa-receipt"></i></div>
            <h3 class="fw-bold"><?= $stats['total_orders'] ?></h3>
            <p class="text-muted mb-0">Orders</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-center p-3">
            <div class="fs-1 text-success mb-1"><i class="fa fa-dollar-sign"></i></div>
            <h3 class="fw-bold">$<?= number_format($stats['total_revenue'], 2) ?></h3>
            <p class="text-muted mb-0">Revenue</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-center p-3">
            <div class="fs-1 text-info mb-1"><i class="fa fa-users"></i></div>
            <h3 class="fw-bold"><?= $stats['total_users'] ?></h3>
            <p class="text-muted mb-0">Customers</p>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-bold">Recent Orders</div>
    <table class="table mb-0">
        <thead class="table-light">
            <tr><th>#</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th><th></th></tr>
        </thead>
        <tbody>
        <?php if (empty($recentOrders)): ?>
            <tr><td colspan="6" class="text-center text-muted py-3">No orders yet.</td></tr>
        <?php else: ?>
        <?php foreach ($recentOrders as $o): ?>
        <tr>
            <td><?= $o['id'] ?></td>
            <td><?= esc($o['customer'] ?? 'Guest') ?></td>
            <td>$<?= number_format($o['total'],2) ?></td>
            <td><span class="badge bg-warning"><?= ucfirst($o['status']) ?></span></td>
            <td><?= date('d M Y', strtotime($o['created_at'])) ?></td>
            <td><a href="<?= base_url('admin/orders/'.$o['id']) ?>" class="btn btn-sm btn-outline-primary">View</a></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
