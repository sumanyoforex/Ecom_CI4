<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php $isEdit = !empty($category); ?>
<div class="d-flex justify-content-between mb-4">
    <h2><?= $isEdit ? 'Edit Category' : 'New Category' ?></h2>
    <a href="<?= base_url('admin/categories') ?>" class="btn btn-outline-secondary">← Back</a>
</div>

<div class="card border-0 shadow-sm p-4 admin-form-500">
    <form method="post" action="<?= $isEdit ? base_url('admin/categories/update/'.$category['id']) : base_url('admin/categories/store') ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label class="form-label">Category Name *</label>
            <input type="text" name="name" class="form-control" required value="<?= esc($category['name'] ?? '') ?>">
        </div>
        <div class="mb-4">
            <label class="form-label">Image URL</label>
            <input type="text" name="image_url" class="form-control" value="<?= esc($category['image_url'] ?? '') ?>" placeholder="https://picsum.photos/seed/name/400/300">
        </div>
        <button class="btn btn-primary"><?= $isEdit ? 'Update' : 'Create' ?> Category</button>
    </form>
</div>

<?= $this->endSection() ?>

