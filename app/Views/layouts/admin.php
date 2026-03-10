<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Admin Panel' ?> | ShopCI4</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body>
<div class="container-fluid">
<div class="row">

    <!-- Sidebar -->
    <div class="col-md-2 sidebar p-0">
        <div class="brand"><i class="fa fa-store me-2"></i>ShopCI4 Admin</div>
        <nav class="nav flex-column">
            <a class="nav-link" href="<?= base_url('admin') ?>"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
            <a class="nav-link" href="<?= base_url('admin/products') ?>"><i class="fa fa-box me-2"></i>Products</a>
            <a class="nav-link" href="<?= base_url('admin/categories') ?>"><i class="fa fa-tags me-2"></i>Categories</a>
            <a class="nav-link" href="<?= base_url('admin/orders') ?>"><i class="fa fa-receipt me-2"></i>Orders</a>
            <a class="nav-link" href="<?= base_url('admin/users') ?>"><i class="fa fa-users me-2"></i>Customers</a>
            <hr class="border-secondary mx-3">
            <a class="nav-link text-danger" href="<?= base_url('admin/logout') ?>"><i class="fa fa-sign-out-alt me-2"></i>Logout</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="col-md-10 main-content">
        <!-- Flash messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible"><button class="btn-close" data-bs-dismiss="alert"></button><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible"><button class="btn-close" data-bs-dismiss="alert"></button><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </div>

</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

