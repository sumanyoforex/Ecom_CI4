<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Your Cart</h2>

<?php if (empty($items)): ?>
    <div class="alert alert-info text-center">
        Your cart is empty. <a href="<?= base_url('shop') ?>">Continue shopping</a>
    </div>
<?php else: ?>
<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="d-flex align-items-center gap-2">
                            <img src="<?= esc($item['image_url']) ?>" width="60" class="rounded">
                            <span><?= esc($item['name']) ?></span>
                        </td>
                        <td>$<?= number_format($item['unit_price'],2) ?></td>
                        <td>
                            <form method="post" action="<?= base_url('cart/update') ?>" class="d-flex gap-1">
                                <?= csrf_field() ?>
                                <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                                <input type="number" name="qty" value="<?= $item['qty'] ?>" min="1" class="form-control form-control-sm" style="width:65px">
                                <button class="btn btn-sm btn-outline-secondary">↺</button>
                            </form>
                        </td>
                        <td>$<?= number_format($item['subtotal'],2) ?></td>
                        <td>
                            <form method="post" action="<?= base_url('cart/remove') ?>">
                                <?= csrf_field() ?>
                                <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                                <button class="btn btn-sm btn-danger">✕</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4">
            <h5>Order Summary</h5>
            <hr>
            <div class="d-flex justify-content-between fw-bold fs-5">
                <span>Total:</span><span>$<?= number_format($total,2) ?></span>
            </div>
            <a href="<?= base_url('checkout') ?>" class="btn btn-success w-100 mt-3">
                Proceed to Checkout
            </a>
            <a href="<?= base_url('shop') ?>" class="btn btn-outline-secondary w-100 mt-2">Continue Shopping</a>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
