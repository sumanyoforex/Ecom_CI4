<?= $this->extend('layouts/main') ?>
<?= $this->section('pageStyles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/checkout.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card p-3 p-md-4 border-0">
            <h3 class="mb-3">Checkout</h3>

            <div class="table-responsive mb-3">
                <table class="table align-middle mb-0">
                    <thead>
                    <tr>
                        <th>Item</th>
                        <th class="text-end">Unit</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= esc($item['name']) ?></td>
                            <td class="text-end">$<?= number_format($item['unit_price'], 2) ?></td>
                            <td class="text-center"><?= (int)$item['qty'] ?></td>
                            <td class="text-end">$<?= number_format($item['subtotal'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="card border-0 p-3 coupon-panel">
                <h6 class="mb-2">Coupon</h6>
                <form method="post" action="<?= base_url('checkout/apply-coupon') ?>" class="d-flex gap-2 mb-2">
                    <?= csrf_field() ?>
                    <input type="text" name="coupon_code" class="form-control" placeholder="Enter code (e.g. WELCOME10)">
                    <button class="btn btn-brand" type="submit">Apply</button>
                </form>
                <?php if (!empty($appliedCouponCode)): ?>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="small text-success">Applied: <strong><?= esc($appliedCouponCode) ?></strong></div>
                        <form method="post" action="<?= base_url('checkout/remove-coupon') ?>">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm btn-outline-danger" type="submit">Remove</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card p-3 p-md-4 border-0 mb-3">
            <h5 class="mb-3">Order Total</h5>
            <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><strong>$<?= number_format($summary['subtotal'], 2) ?></strong></div>
            <div class="d-flex justify-content-between mb-2"><span>Discount</span><strong class="text-success">-$<?= number_format($summary['discount'], 2) ?></strong></div>
            <div class="d-flex justify-content-between mb-2"><span>Shipping</span><strong>$<?= number_format($summary['shipping'], 2) ?></strong></div>
            <div class="d-flex justify-content-between mb-2"><span>Tax</span><strong>$<?= number_format($summary['tax'], 2) ?></strong></div>
            <hr>
            <div class="d-flex justify-content-between fs-5"><span class="fw-bold">Grand Total</span><strong>$<?= number_format($summary['grand_total'], 2) ?></strong></div>
        </div>

        <div class="card p-3 p-md-4 border-0">
            <h5 class="mb-3">Shipping & Payment</h5>
            <form action="<?= base_url('checkout/place') ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Shipping Address</label>
                    <textarea name="address" rows="4" class="form-control" required placeholder="Street, city, state, zip, country..."><?= old('address') ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Payment Method</label>
                    <select name="payment" class="form-select">
                        <option value="COD">Cash on Delivery</option>
                        <option value="CARD">Credit or Debit Card</option>
                        <option value="UPI">UPI</option>
                    </select>
                </div>
                <button class="btn btn-brand w-100 btn-lg" type="submit">Place Secure Order</button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

