<?= $this->extend('layouts/main') ?>
<?= $this->section('pageStyles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/shop.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="market-strip">
    <strong>Today’s Deals:</strong> Discover curated picks, fast shipping products, and startup-price offers across all categories.
</div>

<div class="row g-4">
    <div class="col-lg-3">
        <div class="card border-0 p-3 filters-card">
            <h5 class="fw-bold mb-1">Refine Results</h5>
            <small class="text-muted">Find products faster like a marketplace.</small>

            <form method="post" action="<?= base_url('shop/filter') ?>">
                <?= csrf_field() ?>
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


