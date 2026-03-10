<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php $isEdit = !empty($product); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><?= $isEdit ? 'Edit Product' : 'New Product' ?></h2>
    <a href="<?= base_url('admin/products') ?>" class="btn btn-outline-secondary">← Back</a>
</div>

<div class="card border-0 shadow-sm p-4 admin-form-700">
    <form method="post" action="<?= $isEdit ? base_url('admin/products/update/'.$product['id']) : base_url('admin/products/store') ?>">
        <?= csrf_field() ?>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Product Name *</label>
                <input type="text" name="name" class="form-control" required value="<?= esc($product['name'] ?? '') ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Category *</label>
                <select name="category_id" class="form-select" required>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($product['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                            <?= esc($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" rows="3" class="form-control"><?= esc($product['description'] ?? '') ?></textarea>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Price *</label>
                <input type="number" name="price" step="0.01" class="form-control" required value="<?= $product['price'] ?? '' ?>">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Sale Price</label>
                <input type="number" name="sale_price" step="0.01" class="form-control" value="<?= $product['sale_price'] ?? '' ?>" placeholder="Leave empty for none">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Stock *</label>
                <input type="number" name="stock" class="form-control" required value="<?= $product['stock'] ?? 0 ?>">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Image URL (from online source)</label>
            <input type="text" name="image_url" class="form-control" value="<?= esc($product['image_url'] ?? '') ?>" placeholder="https://picsum.photos/seed/name/600/400">
            <?php if (!empty($product['image_url'])): ?>
                <img loading="lazy" decoding="async" src="<?= esc($product['image_url']) ?>" class="mt-2 rounded admin-image-preview">
            <?php endif; ?>
        </div>

        <div class="mb-4">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="active" <?= ($product['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="draft"  <?= ($product['status'] ?? '') === 'draft'  ? 'selected' : '' ?>>Draft</option>
            </select>
        </div>

        <button class="btn btn-primary btn-lg"><?= $isEdit ? 'Update Product' : 'Create Product' ?></button>
    </form>
</div>

<?= $this->endSection() ?>


