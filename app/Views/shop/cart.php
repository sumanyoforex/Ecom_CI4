<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Your Cart</h2>

<?php if (empty($items)): ?>
    <div class="alert alert-info text-center">
        Your cart is empty. <a href="<?= base_url('shop') ?>">Continue shopping</a>
    </div>
<?php else: ?>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 p-2 p-md-3">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th class="text-end">Price</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Subtotal</th>
                        <th class="text-end">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img loading="lazy" decoding="async" src="<?= esc($item['image_url']) ?>" width="58" class="rounded" alt="<?= esc($item['name']) ?>">
                                    <div>
                                        <div class="fw-semibold"><?= esc($item['name']) ?></div>
                                        <small class="text-muted">Stock: <?= (int)$item['stock'] ?></small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end">$<?= number_format($item['unit_price'], 2) ?></td>
                            <td class="text-center">
                                <form method="post" action="<?= base_url('cart/update') ?>" class="d-inline-flex gap-1 align-items-center">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="cart_id" value="<?= (int)$item['id'] ?>">
                                    <input type="number" name="qty" value="<?= (int)$item['qty'] ?>" min="1" max="<?= (int)$item['stock'] ?>" class="form-control form-control-sm" style="width:72px">
                                    <button class="btn btn-sm btn-outline-secondary" type="submit">Update</button>
                                </form>
                            </td>
                            <td class="text-end">$<?= number_format($item['subtotal'], 2) ?></td>
                            <td class="text-end">
                                <form method="post" action="<?= base_url('cart/remove') ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="cart_id" value="<?= (int)$item['id'] ?>">
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 p-4">
            <h5 class="mb-3">Summary</h5>
            <div class="d-flex justify-content-between fw-semibold">
                <span>Total</span>
                <span>$<?= number_format($total, 2) ?></span>
            </div>
            <a href="<?= base_url('checkout') ?>" class="btn btn-brand w-100 mt-3">Proceed to Checkout</a>
            <a href="<?= base_url('shop') ?>" class="btn btn-outline-secondary w-100 mt-2">Continue Shopping</a>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>

