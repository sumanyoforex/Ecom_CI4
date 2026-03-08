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
                <input type="text" name="name" class="form-control" required minlength="2" maxlength="100" placeholder="John Doe" value="<?= old('name') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required placeholder="you@email.com" value="<?= old('email') ?>">
            </div>
            <div class="mb-1">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required minlength="8" maxlength="72" placeholder="Minimum 8 characters" autocomplete="new-password">
            </div>
            <small class="text-muted d-block mb-3">Use at least 1 uppercase letter, 1 lowercase letter, and 1 number.</small>
            <button class="btn btn-brand w-100 btn-lg" type="submit">Register</button>
        </form>

        <p class="text-center mt-3 mb-0">Already have an account? <a href="<?= base_url('login') ?>">Login</a></p>
    </div>
</div>
</div>

<?= $this->endSection() ?>
