<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
    .market-strip {
        background: linear-gradient(90deg, #1f3b4e, #24536a);
        border: 1px solid color-mix(in oklab, var(--line) 70%, #1f3b4e);
        color: #eaf6ff;
        border-radius: 14px;
        padding: 0.7rem 1rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .market-strip strong {
        color: #ffd95a;
    }

    .filters-card {
        position: sticky;
        top: 84px;
    }

    .market-product-card {
        border-radius: 16px;
        overflow: hidden;
    }

    .market-product-card .card-body {
        gap: 0.35rem;
    }

    .market-title {
        min-height: 2.9rem;
    }

    .market-stars {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.84rem;
        color: #f7b500;
    }

    .market-stars .star-dim {
        color: color-mix(in oklab, #f7b500 38%, #8798a4);
    }

    .market-stars .rating-value {
        color: var(--text);
        margin-left: 0.2rem;
        font-weight: 700;
    }

    .market-stars .rating-count {
        color: var(--muted);
        font-weight: 600;
    }

    .market-ship {
        font-size: 0.82rem;
        color: var(--muted);
    }

    .price-current {
        font-size: 1.45rem;
        font-weight: 800;
        line-height: 1;
    }

    .price-row {
        display: flex;
        align-items: baseline;
        gap: 0.45rem;
        flex-wrap: wrap;
    }

    .btn-market {
        border: 1px solid #d5ad2d;
        background: linear-gradient(180deg, #ffe47a, #ffd24d);
        color: #1b2430;
        font-weight: 700;
        border-radius: 999px;
    }

    .btn-market:hover {
        background: linear-gradient(180deg, #ffe985, #ffd95e);
        color: #141b24;
    }

    @media (max-width: 991.98px) {
        .filters-card {
            position: static;
            top: 0;
        }
    }
</style>

<div class="market-strip">
    <strong>Today’s Deals:</strong> Discover curated picks, fast shipping products, and startup-price offers across all categories.
</div>

<div class="row g-4">
    <div class="col-lg-3">
        <div class="card border-0 p-3 filters-card">
            <h5 class="fw-bold mb-1">Refine Results</h5>
            <small class="text-muted">Find products faster like a marketplace.</small>

            <form method="get" action="<?= base_url('shop') ?>">
                <label class="form-label fw-semibold mt-3">Category</label>
                <?php foreach ($categories as $cat): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="category" value="<?= (int)$cat['id'] ?>" id="cat<?= (int)$cat['id'] ?>" <?= $activeCategoryId == $cat['id'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="cat<?= (int)$cat['id'] ?>"><?= esc($cat['name']) ?></label>
                    </div>
                <?php endforeach; ?>
                <div class="form-check mt-1">
                    <input class="form-check-input" type="radio" name="category" value="0" <?= !$activeCategoryId ? 'checked' : '' ?>>
                    <label class="form-check-label">All Categories</label>
                </div>

                <label class="form-label fw-semibold mt-3">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" value="<?= esc($search) ?>" placeholder="Product name...">

                <label class="form-label fw-semibold mt-3">Price Range</label>
                <div class="row g-2">
                    <div class="col-6">
                        <input type="number" step="0.01" min="0" name="min_price" class="form-control form-control-sm" placeholder="Min" value="<?= esc((string)$minPrice) ?>">
                    </div>
                    <div class="col-6">
                        <input type="number" step="0.01" min="0" name="max_price" class="form-control form-control-sm" placeholder="Max" value="<?= esc((string)$maxPrice) ?>">
                    </div>
                </div>

                <label class="form-label fw-semibold mt-3">Sort By</label>
                <select name="sort" class="form-select form-select-sm">
                    <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest</option>
                    <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
                    <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                    <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Name: A-Z</option>
                </select>

                <button class="btn btn-brand btn-sm w-100 mt-3" type="submit">Apply Filters</button>
                <a href="<?= base_url('shop') ?>" class="btn btn-outline-secondary btn-sm w-100 mt-2">Reset</a>
            </form>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 fw-bold"><?= count($products) ?> Products Found</h5>
            <small class="text-muted">Updated for a marketplace shopping experience</small>
        </div>

        <?php if (empty($products)): ?>
            <div class="alert alert-info">No products found. Try changing your filters.</div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?php foreach ($products as $p): ?>
                    <?php
                    $rating = min(4.9, 3.8 + (((int)$p['id'] % 11) * 0.1));
                    $reviews = 120 + (((int)$p['id'] * 37) % 4200);
                    $price = (float)$p['price'];
                    $sale = $p['sale_price'] !== null ? (float)$p['sale_price'] : null;
                    ?>
                    <div class="col d-flex">
                        <div class="product-card market-product-card h-100 position-relative w-100">
                            <?php if (!empty($p['sale_price'])): ?>
                                <span class="badge badge-sale">SALE</span>
                            <?php endif; ?>
                            <img loading="lazy" decoding="async" src="<?= esc($p['image_url']) ?>" class="card-img-top" alt="<?= esc($p['name']) ?>">
                            <div class="card-body d-flex flex-column p-3">
                                <h6 class="card-title fw-bold mb-1 market-title"><?= esc($p['name']) ?></h6>

                                <div class="market-stars mb-1">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fa-solid fa-star <?= $i <= (int)floor($rating) ? '' : 'star-dim' ?>"></i>
                                    <?php endfor; ?>
                                    <span class="rating-value"><?= number_format($rating, 1) ?></span>
                                    <span class="rating-count">(<?= number_format($reviews) ?>)</span>
                                </div>

                                <div class="price-row mt-auto">
                                    <?php if ($sale !== null): ?>
                                        <span class="text-danger price-current">$<?= number_format($sale, 2) ?></span>
                                        <small class="text-muted text-decoration-line-through">$<?= number_format($price, 2) ?></small>
                                    <?php else: ?>
                                        <span class="price-current">$<?= number_format($price, 2) ?></span>
                                    <?php endif; ?>
                                </div>

                                <small class="market-ship mb-2">FREE delivery by tomorrow</small>
                                <a href="<?= base_url('shop/' . $p['slug']) ?>" class="btn btn-market btn-sm mt-1">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
