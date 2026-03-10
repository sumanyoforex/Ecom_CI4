<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
<div class="col-md-5">
    <div class="card border-0 shadow p-4">
        <h3 class="mb-3 text-center fw-bold">Reset Password</h3>

        <form method="post" action="<?= base_url('reset-password/' . $token) ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control" required minlength="8" maxlength="72" autocomplete="new-password">
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirm" class="form-control" required minlength="8" maxlength="72" autocomplete="new-password">
            </div>
            <small class="text-muted d-block mb-3">Use at least 1 uppercase letter, 1 lowercase letter, and 1 number.</small>
            <button class="btn btn-brand w-100" type="submit">Update Password</button>
        </form>
    </div>
</div>
</div>

<?= $this->endSection() ?>
