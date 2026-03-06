<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | ShopCI4</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #1a1d23; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background: #fff; border-radius: 1rem; padding: 2.5rem; width: 100%; max-width: 400px; box-shadow: 0 10px 40px rgba(0,0,0,.4); }
        .brand { color: #0d6efd; font-size: 1.8rem; font-weight: 800; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="text-center mb-4">
        <div class="brand">ShopCI4</div>
        <p class="text-muted">Admin Portal</p>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('admin/login') ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label class="form-label">Admin Email</label>
            <input type="email" name="email" class="form-control" required placeholder="admin@shop.com">
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button class="btn btn-primary w-100">Login as Admin</button>
    </form>
</div>
</body>
</html>
