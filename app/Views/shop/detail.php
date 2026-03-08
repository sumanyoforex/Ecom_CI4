<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$ratingStats = $ratingStats ?? ['avg_rating' => 0.0, 'rating_count' => 0];
$reviews = $reviews ?? [];
$avg = (float)($ratingStats['avg_rating'] ?? 0);
$count = (int)($ratingStats['rating_count'] ?? 0);
?>

<style>
    .rating-stars i { color: #f7b500; }
    .rating-stars .dim { color: #9fb2bc; }
    .review-card {
        border: 1px solid var(--line);
        border-radius: 14px;
        padding: 0.9rem;
        background: color-mix(in oklab, var(--panel) 92%, var(--surface));
    }
</style>

<div class="row g-4">
    <div class="col-md-7">
        <img loading="lazy" decoding="async" src="<?= esc($product['image_url']) ?>" class="img-fluid rounded shadow-sm" alt="<?= esc($product['name']) ?>">
    </div>
    <div class="col-md-5">
        <h2><?= esc($product['name']) ?></h2>
        <p class="text-muted"><?= esc($product['description']) ?></p>

        <div class="d-flex align-items-center gap-2 mb-3 rating-stars">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <i class="fa-solid fa-star <?= $i <= (int)floor($avg) ? '' : 'dim' ?>"></i>
            <?php endfor; ?>
            <span class="fw-bold"><?= number_format($avg, 1) ?></span>
            <span class="text-muted">(<?= number_format($count) ?> ratings)</span>
        </div>

        <div class="mb-3">
            <?php if ($product['sale_price']): ?>
                <h4 class="text-danger">$<?= number_format($product['sale_price'], 2) ?></h4>
                <p class="text-muted text-decoration-line-through">Was $<?= number_format($product['price'], 2) ?></p>
            <?php else: ?>
                <h4>$<?= number_format($product['price'], 2) ?></h4>
            <?php endif; ?>
            <span class="badge <?= $product['stock'] > 0 ? 'bg-success' : 'bg-danger' ?>">
                <?= $product['stock'] > 0 ? 'In Stock (' . $product['stock'] . ')' : 'Out of Stock' ?>
            </span>
        </div>

        <?php if ($product['stock'] > 0): ?>
        <form action="<?= base_url('cart/add') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <div class="d-flex align-items-center gap-2 mb-3">
                <label>Qty:</label>
                <input type="number" name="qty" value="1" min="1" max="<?= $product['stock'] ?>" class="form-control" style="width:80px">
            </div>
            <button class="btn btn-primary btn-lg w-100">
                <i class="fa fa-cart-plus me-2"></i>Add to Cart
            </button>
        </form>
        <?php endif; ?>

        <a href="<?= base_url('shop') ?>" class="btn btn-link mt-2"><i class="fa fa-arrow-left me-1"></i>Back to Shop</a>
    </div>
</div>

<section class="mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Customer Reviews</h4>
        <div class="small text-muted">Average: <?= number_format($avg, 1) ?> / 5</div>
    </div>

    <?php if (empty($reviews)): ?>
        <div class="alert alert-light border">No reviews yet. Be the first to rate this product.</div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($reviews as $review): ?>
                <div class="col-12">
                    <div class="review-card">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <div class="fw-semibold"><?= esc((string)($review['user_name'] ?? 'Verified Customer')) ?></div>
                                <div class="rating-stars small">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fa-solid fa-star <?= $i <= (int)$review['rating'] ? '' : 'dim' ?>"></i>
                                    <?php endfor; ?>
                                    <span class="ms-1"><?= (int)$review['rating'] ?>/5</span>
                                </div>
                            </div>
                            <div class="small text-muted"><?= !empty($review['created_at']) ? date('d M Y', strtotime((string)$review['created_at'])) : '' ?></div>
                        </div>
                        <?php if (!empty($review['review'])): ?>
                            <p class="mb-0 mt-2"><?= esc((string)$review['review']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?= $this->endSection() ?>
