<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row g-4">
    <div class="col-lg-3">
        <div class="card border-0 p-3">
            <h5 class="fw-bold">Filters</h5>
            <form method="get" action="<?= base_url('shop') ?>">
                <label class="form-label fw-semibold mt-2">Category</label>
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

                <button class="btn btn-brand btn-sm w-100 mt-3" type="submit">Apply</button>
                <a href="<?= base_url('shop') ?>" class="btn btn-outline-secondary btn-sm w-100 mt-2">Reset</a>
            </form>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"><?= count($products) ?> Products Found</h5>
        </div>

        <?php if (empty($products)): ?>
            <div class="alert alert-info">No products found. Try changing your filters.</div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?php foreach ($products as $p): ?>
                    <div class="col">
                        <div class="product-card h-100 position-relative">
                            <?php if (!empty($p['sale_price'])): ?>
                                <span class="badge badge-sale">SALE</span>
                            <?php endif; ?>
                            <img loading="lazy" decoding="async" src="<?= esc($p['image_url']) ?>" class="card-img-top" alt="<?= esc($p['name']) ?>">
                            <div class="card-body d-flex flex-column p-3">
                                <h6 class="card-title fw-bold mb-1"><?= esc($p['name']) ?></h6>
                                <small class="text-muted mb-2">Stock: <?= (int)$p['stock'] ?></small>
                                <div class="mt-auto">
                                    <?php if (!empty($p['sale_price'])): ?>
                                        <span class="text-danger fw-bold">$<?= number_format((float)$p['sale_price'], 2) ?></span>
                                        <small class="text-muted text-decoration-line-through ms-1">$<?= number_format((float)$p['price'], 2) ?></small>
                                    <?php else: ?>
                                        <span class="fw-bold">$<?= number_format((float)$p['price'], 2) ?></span>
                                    <?php endif; ?>
                                </div>
                                <a href="<?= base_url('shop/' . $p['slug']) ?>" class="btn btn-outline-primary btn-sm mt-2">View</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

