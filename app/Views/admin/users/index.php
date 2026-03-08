<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Customers</h2>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead class="table-light">
                <tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Joined</th><th></th></tr>
            </thead>
            <tbody>
            <?php if (empty($users)): ?>
                <tr><td colspan="6" class="text-center py-4 text-muted">No customers registered yet.</td></tr>
            <?php else: ?>
            <?php foreach ($users as $u): ?>
            <tr>
                <td><?= (int)$u['id'] ?></td>
                <td><?= esc($u['name']) ?></td>
                <td><?= esc($u['email']) ?></td>
                <td><span class="badge bg-secondary"><?= esc($u['role']) ?></span></td>
                <td><?= date('d M Y', strtotime((string)$u['created_at'])) ?></td>
                <td><a href="<?= base_url('admin/users/edit/' . $u['id']) ?>" class="btn btn-sm btn-outline-primary">Edit</a></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
