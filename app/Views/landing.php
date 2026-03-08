<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
    .category-slider-shell {
        overflow: hidden;
    }

    .category-slider {
        display: flex;
        gap: 1rem;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scroll-behavior: smooth;
        padding-bottom: 0.25rem;
        scrollbar-width: none;
    }

    .category-slider::-webkit-scrollbar {
        display: none;
    }

    .category-slide {
        flex: 0 0 calc((100% - 3rem) / 4);
        min-width: 220px;
        scroll-snap-align: start;
        text-decoration: none;
        color: inherit;
    }

    .category-nav {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .category-nav-btn {
        border: 1px solid var(--line);
        background: var(--panel);
        color: var(--text);
        width: 38px;
        height: 38px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .category-nav-btn:hover {
        background: color-mix(in oklab, var(--panel-soft) 72%, var(--panel));
    }

    @media (max-width: 991.98px) {
        .category-slide {
            flex: 0 0 calc((100% - 2rem) / 3);
            min-width: 210px;
        }
    }

    @media (max-width: 767.98px) {
        .category-slide {
            flex: 0 0 calc((100% - 1rem) / 2);
            min-width: 170px;
        }
    }
</style>

<section class="panel hero-panel p-4 p-md-5 mb-4 position-relative overflow-hidden">
    <div class="row g-4 align-items-stretch align-items-lg-center">
        <div class="col-lg-7 d-flex flex-column justify-content-center">
            <span class="badge rounded-pill text-bg-light border border-info-subtle mb-3">Fresh Launch</span>
            <h1 class="display-4 fw-bold mb-3">Modern shopping with calm colors, smooth motion, and smarter picks.</h1>
            <p class="lead text-muted mb-4">Experience a premium storefront designed in light blue, light green and white tones with dark mode support.</p>
            <div class="d-flex flex-wrap gap-2">
                <a href="<?= base_url('shop') ?>" class="btn btn-brand btn-lg px-4">Start Shopping</a>
                <?php if (!session()->get('user_id')): ?>
                    <a href="<?= base_url('register') ?>" class="btn btn-outline-secondary btn-lg px-4">Create Account</a>
                <?php else: ?>
                    <a href="<?= base_url('account') ?>" class="btn btn-outline-secondary btn-lg px-4">My Account</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-5 d-flex">
            <div class="card hero-highlight-card p-3 border-0 w-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Highlights</h5>
                    <i class="fa-solid fa-bolt" style="color: var(--accent);"></i>
                </div>
                <ul class="list-unstyled mb-0">
                    <li class="py-2 border-bottom">Responsive storefront on desktop and mobile</li>
                    <li class="py-2 border-bottom">Animated micro-interactions across cards</li>
                    <li class="py-2">Simple login, account, cart, and checkout flow</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Browse Categories</h2>
        <div class="category-nav">
            <button class="category-nav-btn" type="button" data-category-nav="prev" aria-label="Previous categories">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button class="category-nav-btn" type="button" data-category-nav="next" aria-label="Next categories">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
            <a href="<?= base_url('shop') ?>" class="small fw-semibold ms-1">View All</a>
        </div>
    </div>

    <div class="category-slider-shell">
        <div class="category-slider" id="categorySlider">
            <?php foreach ($categories as $cat): ?>
                <a href="<?= base_url('shop?category=' . $cat['id']) ?>" class="category-slide">
                    <div class="product-card h-100">
                        <img loading="lazy" decoding="async" src="<?= esc($cat['image_url']) ?>" class="card-img-top" alt="<?= esc($cat['name']) ?>">
                        <div class="card-body p-3 d-flex align-items-center justify-content-center text-center">
                            <h6 class="mb-0 fw-bold" style="color: var(--text); min-height: auto;"><?= esc($cat['name']) ?></h6>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Featured Products</h2>
        <a href="<?= base_url('shop') ?>" class="small fw-semibold">Shop Collection</a>
    </div>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3">
        <?php foreach ($featured as $p): ?>
            <div class="col d-flex">
                <div class="product-card h-100 position-relative w-100">
                    <?php if (!empty($p['sale_price'])): ?>
                        <span class="badge badge-sale">SALE</span>
                    <?php endif; ?>
                    <img loading="lazy" decoding="async" src="<?= esc($p['image_url']) ?>" alt="<?= esc($p['name']) ?>">
                    <div class="card-body p-3 d-flex flex-column">
                        <h6 class="fw-bold mb-1"><?= esc($p['name']) ?></h6>
                        <div class="mt-auto">
                            <?php if (!empty($p['sale_price'])): ?>
                                <span class="fw-bold text-danger">$<?= number_format($p['sale_price'], 2) ?></span>
                                <small class="text-muted text-decoration-line-through ms-1">$<?= number_format($p['price'], 2) ?></small>
                            <?php else: ?>
                                <span class="fw-bold">$<?= number_format($p['price'], 2) ?></span>
                            <?php endif; ?>
                        </div>
                        <a href="<?= base_url('shop/' . $p['slug']) ?>" class="btn btn-outline-primary btn-sm mt-3">View Details</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<script>
    (function () {
        const slider = document.getElementById('categorySlider');
        if (!slider) return;

        const prevBtn = document.querySelector('[data-category-nav="prev"]');
        const nextBtn = document.querySelector('[data-category-nav="next"]');

        const getGap = function () {
            return parseFloat(getComputedStyle(slider).gap || '16');
        };

        const getStep = function () {
            const firstCard = slider.querySelector('.category-slide');
            if (!firstCard) return slider.clientWidth * 0.8;
            return firstCard.getBoundingClientRect().width + getGap();
        };

        const slide = function (direction) {
            slider.scrollBy({ left: direction * getStep(), behavior: 'smooth' });
        };

        if (prevBtn) {
            prevBtn.addEventListener('click', function () { slide(-1); });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () { slide(1); });
        }

        let autoTimer = null;

        const stopAuto = function () {
            if (autoTimer) {
                clearInterval(autoTimer);
                autoTimer = null;
            }
        };

        const startAuto = function () {
            stopAuto();
            if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                return;
            }

            autoTimer = setInterval(function () {
                const atEnd = slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 4;
                if (atEnd) {
                    slider.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    slide(1);
                }
            }, 2600);
        };

        slider.addEventListener('mouseenter', stopAuto);
        slider.addEventListener('mouseleave', startAuto);
        slider.addEventListener('touchstart', stopAuto, { passive: true });
        slider.addEventListener('touchend', startAuto, { passive: true });
        slider.addEventListener('focusin', stopAuto);
        slider.addEventListener('focusout', startAuto);

        startAuto();
    })();
</script>

<?= $this->endSection() ?>
