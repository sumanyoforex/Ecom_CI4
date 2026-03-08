<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Customer</h2>
    <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card border-0 shadow-sm p-4" style="max-width:720px;">
    <form method="post" action="<?= base_url('admin/users/update/' . $user['id']) ?>">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required minlength="2" maxlength="100" value="<?= esc(old('name', $user['name'])) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required value="<?= esc(old('email', $user['email'])) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Role</label>
            <?php $currentRole = old('role', $user['role'] ?? 'customer'); ?>
            <select name="role" class="form-select" required>
                <option value="customer" <?= $currentRole === 'customer' ? 'selected' : '' ?>>Customer</option>
                <option value="support" <?= $currentRole === 'support' ? 'selected' : '' ?>>Support</option>
                <option value="manager" <?= $currentRole === 'manager' ? 'selected' : '' ?>>Manager</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">New Password (optional)</label>
            <input type="password" name="password" class="form-control" minlength="8" maxlength="72" placeholder="Leave empty to keep current password">
            <small class="text-muted">If provided, password must include uppercase, lowercase, and number.</small>
        </div>

        <button class="btn btn-primary" type="submit">Update User</button>
    </form>
</div>

<?= $this->endSection() ?>
