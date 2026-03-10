<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'ShopCI4' ?></title>
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
    <?= $this->renderSection('pageStyles') ?>
</head>
<body>
<div class="site-shell">
    <nav class="navbar navbar-expand-lg navbar-light glass-nav sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                <i class="fa-solid fa-bag-shopping me-2 brand-icon"></i>ShopCI4
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('/') ?>">Landing</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('shop') ?>">Shop</a></li>
                </ul>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button class="theme-btn" id="themeToggle" type="button" aria-label="Toggle dark mode">
                        <i class="fa-solid fa-moon"></i>
                    </button>
                    <?php if (session()->get('user_id')): ?>
                        <a class="nav-link" href="<?= base_url('cart') ?>"><i class="fa fa-shopping-cart me-1"></i>Cart</a>
                        <a class="nav-link" href="<?= base_url('account') ?>">My Orders</a>
                        <a class="btn btn-brand btn-sm px-3" href="<?= base_url('logout') ?>">Logout</a>
                    <?php else: ?>
                        <a class="nav-link" href="<?= base_url('login') ?>">Login</a>
                        <a class="btn btn-brand btn-sm px-3" href="<?= base_url('register') ?>">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-3">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    </div>

    <main class="container py-4 fade-in">
        <?= $this->renderSection('content') ?>
    </main>

    <footer class="site-footer py-4">
        <div class="container">
            <div class="row g-3 align-items-center">
                <div class="col-12 col-md-4 text-center text-md-start">
                    <strong class="footer-brand-text">ShopCI4</strong>
                    <div class="small">Startup-ready e-commerce experience in light blue, light green, and white.</div>
                </div>
                <div class="col-12 col-md-4 text-center">
                    <a class="me-3" href="<?= base_url('/') ?>">Home</a>
                    <a class="me-3" href="<?= base_url('shop') ?>">Shop</a>
                    <a href="<?= base_url('login') ?>">Account</a>
                </div>
                <div class="col-12 col-md-4 text-center text-md-end small">
                    &copy; <?= date('Y') ?> ShopCI4. All rights reserved.
                </div>
            </div>
        </div>
    </footer>
</div>

<div class="popup-wrap" id="orbPopup" aria-hidden="true">
    <div class="popup-card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Meet Nova, Your Shopping Guide</h5>
            <button class="btn btn-sm btn-outline-secondary" id="closePopup" type="button">Close</button>
        </div>
        <p class="text-muted mt-2 mb-2">Nova highlights curated deals and checkout-ready offers in real time.</p>

        <div class="mascot-scene" role="img" aria-label="Animated shopping assistant character">
            <div class="mascot-shadow"></div>
            <div class="mascot">
                <div class="mascot-head">
                    <span class="mascot-eye left"></span>
                    <span class="mascot-eye right"></span>
                    <span class="mascot-mouth"></span>
                </div>
                <span class="mascot-arm left"></span>
                <span class="mascot-arm right"></span>
                <div class="mascot-body"></div>
                <div class="mascot-leg-wrap">
                    <span class="mascot-leg"></span>
                    <span class="mascot-leg"></span>
                </div>
            </div>
        </div>

        <a href="<?= base_url('shop') ?>" class="btn btn-brand w-100">Explore Featured Collection</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/main.js') ?>"></script>
<?= $this->renderSection('pageScripts') ?>
</body>
</html>











