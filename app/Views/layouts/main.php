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
    <style>
        :root {
            --bg: #eef8ff;
            --panel: #ffffff;
            --panel-soft: #f3fbff;
            --surface: #e9f8f2;
            --text: #153540;
            --muted: #5c7a85;
            --primary: #54b7ff;
            --accent: #59d9b1;
            --line: #cde8ef;
            --btn-text: #072a35;
            --shadow: 0 10px 28px rgba(53, 125, 141, 0.14);
        }

        html[data-theme="dark"] {
            --bg: #08171f;
            --panel: #102731;
            --panel-soft: #123341;
            --surface: #11323b;
            --text: #e8f6fb;
            --muted: #a9c4ce;
            --primary: #74c9ff;
            --accent: #73e2bf;
            --line: #2b5764;
            --btn-text: #06232d;
            --shadow: 0 12px 32px rgba(0, 0, 0, 0.35);
        }

        :root,
        html[data-theme="dark"] {
            --bs-body-bg: var(--bg);
            --bs-body-color: var(--text);
            --bs-secondary-color: var(--muted);
            --bs-tertiary-color: var(--muted);
            --bs-border-color: var(--line);
            --bs-card-bg: var(--panel);
            --bs-card-color: var(--text);
            --bs-heading-color: var(--text);
            --bs-link-color: var(--primary);
            --bs-link-hover-color: color-mix(in oklab, var(--primary) 70%, white);
        }

        body {
            background:
                radial-gradient(circle at 15% 10%, rgba(87, 184, 255, 0.2), transparent 28%),
                radial-gradient(circle at 85% 0%, rgba(88, 214, 176, 0.22), transparent 32%),
                var(--bg);
            color: var(--text);
            font-family: 'Manrope', sans-serif;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand { font-family: 'Poppins', sans-serif; }

        a { color: var(--primary); }
        a:hover { color: color-mix(in oklab, var(--primary) 72%, white); }

        .text-muted,
        .text-body-secondary,
        .lead.text-muted,
        small.text-muted {
            color: var(--muted) !important;
        }

        html[data-theme="dark"] .text-bg-light {
            background: #ddf3ff !important;
            color: #0f323d !important;
        }

        .site-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .glass-nav {
            background: color-mix(in oklab, var(--panel) 88%, transparent);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--line);
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: 0.4px;
            color: var(--text) !important;
        }

        .nav-link {
            color: var(--text) !important;
            font-weight: 600;
            opacity: 0.95;
        }

        .nav-link:hover { color: var(--primary) !important; }

        .navbar-toggler {
            border: 1px solid var(--line);
        }

        html[data-theme="dark"] .navbar-toggler-icon {
            filter: invert(1) brightness(2);
        }

        .theme-btn {
            border: 1px solid var(--line);
            color: var(--text);
            background: var(--panel);
            border-radius: 999px;
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-brand {
            background: linear-gradient(130deg, var(--primary), var(--accent));
            color: var(--btn-text);
            border: 0;
            font-weight: 700;
        }

        .btn-brand:hover { filter: brightness(1.05); }

        .btn-outline-secondary,
        .btn-outline-primary {
            color: var(--text);
            border-color: color-mix(in oklab, var(--line) 65%, var(--muted));
        }

        .btn-outline-secondary:hover,
        .btn-outline-primary:hover {
            color: var(--text);
            background: color-mix(in oklab, var(--panel-soft) 78%, var(--panel));
            border-color: var(--line);
        }

        .form-control,
        .form-select {
            background: color-mix(in oklab, var(--panel) 90%, var(--surface));
            border-color: var(--line);
            color: var(--text);
        }

        .form-control::placeholder { color: var(--muted); }

        .form-control:focus,
        .form-select:focus {
            background: var(--panel);
            color: var(--text);
            border-color: color-mix(in oklab, var(--primary) 65%, var(--line));
            box-shadow: 0 0 0 .18rem color-mix(in oklab, var(--primary) 28%, transparent);
        }

        .card,
        .product-card,
        .panel {
            border: 1px solid var(--line);
            background: var(--panel);
            border-radius: 18px;
            box-shadow: var(--shadow);
        }

        .panel {
            background: linear-gradient(145deg, color-mix(in oklab, var(--panel) 88%, var(--surface)), var(--panel));
        }

        .hero-panel {
            min-height: clamp(360px, 52vh, 540px);
        }

        .hero-highlight-card {
            background: color-mix(in oklab, var(--panel) 84%, var(--surface));
            justify-content: center;
        }

        .product-card {
            display: flex;
            flex-direction: column;
            overflow: hidden;
            height: 100%;
            transition: transform 0.28s ease, box-shadow 0.28s ease;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 36px rgba(73, 135, 148, 0.24);
        }

        .product-card img {
            height: 230px;
            width: 100%;
            object-fit: cover;
            object-position: center;
            background: transparent;
            padding: 0;
        }

        .product-card .card-body {
            display: flex;
            flex-direction: column;
            gap: .3rem;
        }

        .product-card h6,
        .product-card .card-title {
            min-height: 2.8rem;
            line-height: 1.35;
            margin-bottom: .25rem;
        }

        .badge-sale {
            position: absolute;
            top: 12px;
            right: 12px;
            border-radius: 999px;
            background: #ff7e8e !important;
        }

        .table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--text);
            --bs-table-border-color: var(--line);
        }

        .table-light {
            --bs-table-bg: color-mix(in oklab, var(--surface) 84%, var(--panel));
            --bs-table-color: var(--text);
        }

        .site-footer {
            margin-top: auto;
            background: color-mix(in oklab, var(--surface) 75%, var(--panel));
            border-top: 1px solid var(--line);
            color: var(--muted);
        }

        .site-footer a {
            color: var(--text);
            text-decoration: none;
            font-weight: 600;
        }

        .site-footer a:hover { color: var(--primary); }

        .fade-in {
            animation: fadeInUp 0.7s ease both;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .popup-wrap {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(2, 26, 35, 0.55);
            z-index: 1100;
            padding: 1rem;
        }

        .popup-wrap.show {
            display: flex;
            animation: popupIn 0.35s ease;
        }

        @keyframes popupIn {
            from { opacity: 0; transform: scale(0.96); }
            to { opacity: 1; transform: scale(1); }
        }

        .popup-card {
            width: min(92vw, 540px);
            border-radius: 24px;
            border: 1px solid var(--line);
            background: var(--panel);
            box-shadow: var(--shadow);
            padding: 1.25rem;
            position: relative;
            overflow: hidden;
        }

        .popup-card::before {
            content: '';
            position: absolute;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(87,184,255,.32), transparent 70%);
            top: -70px;
            right: -80px;
        }

        .mascot-scene {
            width: 190px;
            margin: 0 auto 1rem;
            position: relative;
            animation: mascotFloat 3.4s ease-in-out infinite;
        }

        .mascot-shadow {
            width: 110px;
            height: 16px;
            border-radius: 50%;
            background: rgba(12, 44, 55, 0.18);
            margin: 0 auto;
            filter: blur(2px);
            animation: shadowPulse 3.4s ease-in-out infinite;
        }

        .mascot {
            width: 160px;
            margin: 0 auto;
            position: relative;
        }

        .mascot-head {
            width: 110px;
            height: 88px;
            border-radius: 42px;
            background: linear-gradient(140deg, #7cd2ff, #7ce6c6);
            margin: 0 auto;
            border: 2px solid rgba(255, 255, 255, .6);
            box-shadow: 0 8px 24px rgba(52, 130, 141, 0.3);
            position: relative;
        }

        .mascot-eye {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #0d2f39;
            position: absolute;
            top: 34px;
            animation: blink 5s ease-in-out infinite;
        }

        .mascot-eye.left { left: 28px; }
        .mascot-eye.right { right: 28px; }

        .mascot-mouth {
            width: 34px;
            height: 14px;
            border-bottom: 3px solid #0d2f39;
            border-radius: 0 0 24px 24px;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            bottom: 18px;
        }

        .mascot-body {
            width: 95px;
            height: 84px;
            border-radius: 30px;
            background: linear-gradient(150deg, #5bbdff, #4fd9ad);
            margin: -6px auto 0;
            position: relative;
            border: 2px solid rgba(255, 255, 255, .55);
        }

        .mascot-arm {
            width: 46px;
            height: 12px;
            border-radius: 10px;
            background: linear-gradient(90deg, #69c7ff, #57d9b1);
            position: absolute;
            top: 110px;
            transform-origin: left center;
        }

        .mascot-arm.left {
            left: 8px;
            transform: rotate(25deg);
        }

        .mascot-arm.right {
            right: 8px;
            transform: rotate(-20deg);
            animation: wave 2s ease-in-out infinite;
        }

        .mascot-leg-wrap {
            display: flex;
            justify-content: center;
            gap: 22px;
            margin-top: 5px;
        }

        .mascot-leg {
            width: 14px;
            height: 28px;
            border-radius: 8px;
            background: linear-gradient(180deg, #56c6ff, #4ccfbb);
        }

        @keyframes mascotFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @keyframes shadowPulse {
            0%, 100% { transform: scale(1); opacity: 0.25; }
            50% { transform: scale(0.86); opacity: 0.18; }
        }

        @keyframes blink {
            0%, 46%, 48%, 100% { transform: scaleY(1); }
            47% { transform: scaleY(0.12); }
        }

        @keyframes wave {
            0%, 100% { transform: rotate(-20deg); }
            50% { transform: rotate(-45deg); }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; }
        }

        @media (max-width: 991.98px) {
            .navbar-collapse {
                margin-top: .75rem;
                background: var(--panel);
                border: 1px solid var(--line);
                border-radius: 14px;
                padding: .75rem;
            }

            .popup-card {
                width: min(95vw, 460px);
            }
        }

        @media (max-width: 767.98px) {
            main.container {
                padding-left: 0.9rem;
                padding-right: 0.9rem;
            }

            .panel {
                border-radius: 14px;
            }

            .product-card img {
                height: 200px;
            }
        }
    </style>
</head>
<body>
<div class="site-shell">
    <nav class="navbar navbar-expand-lg navbar-light glass-nav sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                <i class="fa-solid fa-bag-shopping me-2" style="color: var(--primary);"></i>ShopCI4
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
                    <strong style="color: var(--text);">ShopCI4</strong>
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
<script>
    (function () {
        const root = document.documentElement;
        const btn = document.getElementById('themeToggle');
        const icon = btn ? btn.querySelector('i') : null;
        const nav = document.querySelector('.glass-nav');
        const saved = localStorage.getItem('theme');
        const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        const initialTheme = saved || (prefersDark ? 'dark' : 'light');

        function applyTheme(theme) {
            root.setAttribute('data-theme', theme);
            if (nav) {
                nav.classList.toggle('navbar-dark', theme === 'dark');
                nav.classList.toggle('navbar-light', theme !== 'dark');
            }
            if (icon) {
                icon.className = theme === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
            }
        }

        applyTheme(initialTheme);

        if (btn) {
            btn.addEventListener('click', function () {
                const current = root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
                const next = current === 'dark' ? 'light' : 'dark';
                localStorage.setItem('theme', next);
                applyTheme(next);
            });
        }

        const popup = document.getElementById('orbPopup');
        const close = document.getElementById('closePopup');

        if (popup && !sessionStorage.getItem('popupSeen')) {
            window.setTimeout(function () {
                popup.classList.add('show');
                popup.setAttribute('aria-hidden', 'false');
                sessionStorage.setItem('popupSeen', '1');
            }, 800);
        }

        if (close && popup) {
            close.addEventListener('click', function () {
                popup.classList.remove('show');
                popup.setAttribute('aria-hidden', 'true');
            });

            popup.addEventListener('click', function (event) {
                if (event.target === popup) {
                    popup.classList.remove('show');
                    popup.setAttribute('aria-hidden', 'true');
                }
            });
        }
    })();
</script>
</body>
</html>










