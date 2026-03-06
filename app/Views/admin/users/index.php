<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Customers</h2>

<div class="card border-0 shadow-sm">
    <table class="table mb-0">
        <thead class="table-light">
            <tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Joined</th></tr>
        </thead>
        <tbody>
        <?php if (empty($users)): ?>
            <tr><td colspan="5" class="text-center py-4 text-muted">No customers registered yet.</td></tr>
        <?php else: ?>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= esc($u['name']) ?></td>
            <td><?= esc($u['email']) ?></td>
            <td><span class="badge bg-secondary"><?= $u['role'] ?></span></td>
            <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
