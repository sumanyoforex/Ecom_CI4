<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
<div class="col-md-5">
    <div class="card border-0 shadow p-4">
        <h3 class="mb-4 text-center fw-bold">Login</h3>

        <form method="post" action="<?= base_url('login') ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required placeholder="you@email.com">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Remember me (30 days)</label>
            </div>
            <button class="btn btn-primary w-100 btn-lg">Login</button>
        </form>

        <p class="text-center mt-3">Don't have an account? <a href="<?= base_url('register') ?>">Register</a></p>
    </div>
</div>
</div>

<?= $this->endSection() ?>
