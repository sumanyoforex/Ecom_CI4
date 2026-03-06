<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
<div class="col-md-5">
    <div class="card border-0 shadow p-4">
        <h3 class="mb-4 text-center fw-bold">Create Account</h3>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $e): ?><div><?= esc($e) ?></div><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('register') ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required placeholder="John Doe">
            </div>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required placeholder="you@email.com">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Min 6 characters">
            </div>
            <button class="btn btn-primary w-100 btn-lg">Register</button>
        </form>

        <p class="text-center mt-3">Already have an account? <a href="<?= base_url('login') ?>">Login</a></p>
    </div>
</div>
</div>

<?= $this->endSection() ?>
