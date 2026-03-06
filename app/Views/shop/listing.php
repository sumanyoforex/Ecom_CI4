<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row">
    <!-- Sidebar Filters -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <h5 class="fw-bold">Filters</h5>
            <form method="get" action="<?= base_url('shop') ?>">
                <!-- Category filter -->
                <label class="form-label fw-semibold mt-2">Category</label>
                <?php foreach ($categories as $cat): ?>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="category" value="<?= $cat['id'] ?>"
                        id="cat<?= $cat['id'] ?>" <?= $activeCategoryId == $cat['id'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="cat<?= $cat['id'] ?>"><?= esc($cat['name']) ?></label>
                </div>
                <?php endforeach; ?>
                <div class="form-check mt-1">
                    <input class="form-check-input" type="radio" name="category" value="0" <?= !$activeCategoryId ? 'checked' : '' ?>>
                    <label class="form-check-label">All Categories</label>
                </div>

                <!-- Search -->
                <label class="form-label fw-semibold mt-3">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" value="<?= esc($search) ?>" placeholder="Product name...">

                <button class="btn btn-primary btn-sm w-100 mt-3">Apply</button>
                <a href="<?= base_url('shop') ?>" class="btn btn-outline-secondary btn-sm w-100 mt-1">Reset</a>
            </form>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5><?= count($products) ?> Products Found</h5>
        </div>

        <?php if (empty($products)): ?>
            <div class="alert alert-info">No products found. Try different filters.</div>
        <?php else: ?>
        <div class="row row-cols-2 row-cols-md-3 g-3">
            <?php foreach ($products as $p): ?>
            <div class="col">
                <div class="card product-card h-100 position-relative">
                    <?php if ($p['sale_price']): ?>
                        <span class="badge bg-danger badge-sale">SALE</span>
                    <?php endif; ?>
                    <img src="<?= esc($p['image_url']) ?>" class="card-img-top" alt="<?= esc($p['name']) ?>">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title"><?= esc($p['name']) ?></h6>
                        <div class="mt-auto">
                            <?php if ($p['sale_price']): ?>
                                <span class="text-danger fw-bold">$<?= number_format($p['sale_price'],2) ?></span>
                                <small class="text-muted text-decoration-line-through ms-1">$<?= number_format($p['price'],2) ?></small>
                            <?php else: ?>
                                <span class="fw-bold">$<?= number_format($p['price'],2) ?></span>
                            <?php endif; ?>
                        </div>
                        <a href="<?= base_url('shop/'.$p['slug']) ?>" class="btn btn-outline-primary btn-sm mt-2">View</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
