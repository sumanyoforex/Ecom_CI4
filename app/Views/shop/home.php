<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Hero Banner -->
<div class="p-5 mb-4 bg-dark text-white rounded-3 text-center" style="background: linear-gradient(135deg,#0d6efd,#6610f2) !important;">
    <h1 class="display-5 fw-bold">Welcome to ShopCI4</h1>
    <p class="lead">Discover amazing products at great prices.</p>
    <a href="<?= base_url('shop') ?>" class="btn btn-light btn-lg">Shop Now</a>
</div>

<!-- Categories -->
<h2 class="mb-3">Browse Categories</h2>
<div class="row row-cols-2 row-cols-md-3 g-3 mb-5">
    <?php foreach ($categories as $cat): ?>
    <div class="col">
        <a href="<?= base_url('shop?category=' . $cat['id']) ?>" class="text-decoration-none">
            <div class="card product-card">
                <img src="<?= esc($cat['image_url']) ?>" class="card-img-top" alt="<?= esc($cat['name']) ?>">
                <div class="card-body text-center">
                    <h6 class="card-title mb-0 fw-semibold"><?= esc($cat['name']) ?></h6>
                </div>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>

<!-- Featured Products -->
<h2 class="mb-3">Featured Products</h2>
<div class="row row-cols-2 row-cols-md-4 g-3">
    <?php foreach ($featured as $p): ?>
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
                        <span class="text-danger fw-bold">$<?= number_format($p['sale_price'], 2) ?></span>
                        <small class="text-muted text-decoration-line-through ms-1">$<?= number_format($p['price'], 2) ?></small>
                    <?php else: ?>
                        <span class="fw-bold">$<?= number_format($p['price'], 2) ?></span>
                    <?php endif; ?>
                </div>
                <a href="<?= base_url('shop/' . $p['slug']) ?>" class="btn btn-outline-primary btn-sm mt-2">View</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>
