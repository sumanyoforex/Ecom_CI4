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
                <input type="email" name="email" class="form-control" required placeholder="you@email.com" value="<?= old('email') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required minlength="8" autocomplete="current-password">
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" name="remember" class="form-check-input" id="remember" value="1">
                <label class="form-check-label" for="remember">Remember me (30 days)</label>
            </div>
            <button class="btn btn-brand w-100 btn-lg" type="submit">Login</button>
            <div class="text-end mt-2"><a class="small" href="<?= base_url('forgot-password') ?>">Forgot password?</a></div>
        </form>

        <p class="text-center mt-3 mb-0">Don't have an account? <a href="<?= base_url('register') ?>">Register</a></p>
    </div>
</div>
</div>

<?= $this->endSection() ?>
