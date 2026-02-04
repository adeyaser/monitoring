<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="dashboard-card">
                <div class="card-header-custom mb-4">
                    <h5 class="card-title mb-0">My Profile</h5>
                    <span class="text-secondary small">Update your personal information</span>
                </div>

                <form action="<?= base_url('profile/update') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0">
                                <i class="bi bi-person text-secondary"></i>
                            </span>
                            <input type="text" name="username" class="form-control border-start-0 ps-0" 
                                value="<?= esc($user['username']) ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0">
                                <i class="bi bi-card-heading text-secondary"></i>
                            </span>
                            <input type="text" name="full_name" class="form-control border-start-0 ps-0" 
                                value="<?= esc($user['full_name']) ?>" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-secondary small">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0">
                                <i class="bi bi-envelope text-secondary"></i>
                            </span>
                            <input type="email" name="email" class="form-control border-start-0 ps-0" 
                                value="<?= esc($user['email']) ?>" required>
                        </div>
                    </div>

                    <hr class="border-secondary opacity-25 my-4">

                    <h6 class="mb-3 text-warning">Change Password</h6>
                    <p class="text-secondary small mb-3">Leave blank if you don't want to change password</p>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0">
                                <i class="bi bi-lock text-secondary"></i>
                            </span>
                            <input type="password" name="password" class="form-control border-start-0 ps-0">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-secondary small">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0">
                                <i class="bi bi-lock-fill text-secondary"></i>
                            </span>
                            <input type="password" name="confirm_password" class="form-control border-start-0 ps-0">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">Back to Dashboard</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
