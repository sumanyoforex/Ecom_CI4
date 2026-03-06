<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-7">
        <img src="<?= esc($product['image_url']) ?>" class="img-fluid rounded shadow-sm" alt="<?= esc($product['name']) ?>">
    </div>
    <div class="col-md-5">
        <h2><?= esc($product['name']) ?></h2>
        <p class="text-muted"><?= esc($product['description']) ?></p>

        <div class="mb-3">
            <?php if ($product['sale_price']): ?>
                <h4 class="text-danger">$<?= number_format($product['sale_price'],2) ?></h4>
                <p class="text-muted text-decoration-line-through">Was $<?= number_format($product['price'],2) ?></p>
            <?php else: ?>
                <h4>$<?= number_format($product['price'],2) ?></h4>
            <?php endif; ?>
            <span class="badge <?= $product['stock'] > 0 ? 'bg-success' : 'bg-danger' ?>">
                <?= $product['stock'] > 0 ? 'In Stock ('.$product['stock'].')' : 'Out of Stock' ?>
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

<?= $this->endSection() ?>
