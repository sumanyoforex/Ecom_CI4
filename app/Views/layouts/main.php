<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'ShopCI4' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .navbar-brand { font-weight: 700; font-size: 1.5rem; }
        .product-card { transition: transform .2s; border: none; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 6px 20px rgba(0,0,0,.12); }
        .product-card img { height: 200px; object-fit: cover; }
        .badge-sale { position: absolute; top: 10px; right: 10px; }
        footer { background: #212529; color: #adb5bd; padding: 2rem 0; margin-top: 4rem; }
    </style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= base_url('/') ?>">
            <i class="fa fa-shopping-bag me-2"></i>ShopCI4
        </a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/') ?>">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('shop') ?>">Shop</a></li>
            </ul>

            <ul class="navbar-nav align-items-center">
                <!-- Cart icon -->
                <li class="nav-item me-2">
                    <a class="nav-link" href="<?= base_url('cart') ?>">
                        <i class="fa fa-shopping-cart"></i> Cart
                    </a>
                </li>

                <?php if (session()->get('user_id')): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('account') ?>">My Orders</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('logout') ?>">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('login') ?>">Login</a></li>
                    <li class="nav-item"><a class="btn btn-primary btn-sm ms-2" href="<?= base_url('register') ?>">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Flash Messages -->
<div class="container mt-3">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible"><button class="btn-close" data-bs-dismiss="alert"></button><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible"><button class="btn-close" data-bs-dismiss="alert"></button><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
</div>

<!-- Page Content -->
<main class="container py-4">
    <?= $this->renderSection('content') ?>
</main>

<footer class="text-center">
    <p class="mb-0">&copy; <?= date('Y') ?> ShopCI4. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
