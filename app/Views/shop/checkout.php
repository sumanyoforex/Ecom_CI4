<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
<div class="col-md-8">
<h2 class="mb-4">Checkout</h2>
<div class="row">
    <!-- Order Summary -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">Order Summary</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= esc($item['name']) ?> × <?= $item['qty'] ?></td>
                        <td class="text-end">$<?= number_format($item['subtotal'],2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="table-light fw-bold">
                        <td>Total</td>
                        <td class="text-end">$<?= number_format($total,2) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Shipping Form -->
    <div class="col-md-6">
        <form action="<?= base_url('checkout/place') ?>" method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Shipping Address <span class="text-danger">*</span></label>
                <textarea name="address" rows="4" class="form-control" required placeholder="House No, Street, City, ZIP..."></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Payment Method</label>
                <select name="payment" class="form-select">
                    <option value="COD">Cash on Delivery</option>
                    <option value="Card">Credit/Debit Card</option>
                    <option value="UPI">UPI</option>
                </select>
            </div>
            <button class="btn btn-success w-100 btn-lg">Place Order</button>
        </form>
    </div>
</div>
</div>
</div>

<?= $this->endSection() ?>
