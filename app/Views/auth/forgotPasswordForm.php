<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
<div class="col-md-5">
    <div class="card border-0 shadow p-4">
        <h3 class="mb-3 text-center fw-bold">Forgot Password</h3>
        <p class="text-muted text-center small">Customer accounts only. Admin password is managed from server environment settings.</p>

        <form method="post" action="<?= base_url('forgot-password') ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required placeholder="you@email.com" value="<?= old('email') ?>">
            </div>
            <button class="btn btn-brand w-100" type="submit">Send Reset Link</button>
        </form>

        <?php if (session()->getFlashdata('dev_reset_link')): ?>
            <div class="alert alert-info mt-3 mb-0 small">
                Development reset link: <a href="<?= session()->getFlashdata('dev_reset_link') ?>">Open link</a>
            </div>
        <?php endif; ?>

        <p class="text-center mt-3 mb-0"><a href="<?= base_url('login') ?>">Back to login</a></p>
    </div>
</div>
</div>

<?= $this->endSection() ?>
