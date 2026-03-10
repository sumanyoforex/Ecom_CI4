<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Categories</h2>
    <a href="<?= base_url('admin/categories/create') ?>" class="btn btn-primary">+ New Category</a>
</div>

<div class="card border-0 shadow-sm">
    <table class="table mb-0">
        <thead class="table-light"><tr><th>Image</th><th>Name</th><th>Slug</th><th>Actions</th></tr></thead>
        <tbody>
        <?php if (empty($categories)): ?>
            <tr><td colspan="4" class="text-center py-4 text-muted">No categories yet.</td></tr>
        <?php else: ?>
        <?php foreach ($categories as $cat): ?>
        <tr>
            <td><img loading="lazy" decoding="async" src="<?= esc($cat['image_url']) ?>" width="60" class="rounded"></td>
            <td><?= esc($cat['name']) ?></td>
            <td><code><?= esc($cat['slug']) ?></code></td>
            <td>
                <a href="<?= base_url('admin/categories/edit/'.$cat['id']) ?>" class="btn btn-sm btn-outline-warning">Edit</a>
                <a href="<?= base_url('admin/categories/delete/'.$cat['id']) ?>" class="btn btn-sm btn-outline-danger"
                   onclick="return confirm('Delete this category?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>

