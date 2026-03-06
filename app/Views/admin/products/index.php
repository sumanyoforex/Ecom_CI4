<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><?= $title ?? 'Products' ?></h2>
    <a href="<?= base_url('admin/products/create') ?>" class="btn btn-primary">+ New Product</a>
</div>

<div class="card border-0 shadow-sm">
    <table class="table mb-0">
        <thead class="table-light">
            <tr><th>Image</th><th>Name</th><th>Price</th><th>Sale</th><th>Stock</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php if (empty($products)): ?>
            <tr><td colspan="7" class="text-center py-4 text-muted">No products yet.</td></tr>
        <?php else: ?>
        <?php foreach ($products as $p): ?>
        <tr>
            <td><img src="<?= esc($p['image_url']) ?>" width="60" class="rounded"></td>
            <td><?= esc($p['name']) ?></td>
            <td>$<?= number_format($p['price'],2) ?></td>
            <td><?= $p['sale_price'] ? '$'.number_format($p['sale_price'],2) : '—' ?></td>
            <td><?= $p['stock'] ?></td>
            <td><span class="badge bg-<?= $p['status']==='active' ? 'success' : 'secondary' ?>"><?= $p['status'] ?></span></td>
            <td>
                <a href="<?= base_url('admin/products/edit/'.$p['id']) ?>" class="btn btn-sm btn-outline-warning">Edit</a>
                <a href="<?= base_url('admin/products/delete/'.$p['id']) ?>" class="btn btn-sm btn-outline-danger"
                   onclick="return confirm('Delete this product?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
