<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="<?= base_url('acl/users') ?>">Users</a></li>
                <li class="breadcrumb-item active"><?= isset($user) ? 'Edit' : 'Tambah' ?> User</li>
            </ol>
        </nav>
        <h4 class="mb-1"><?= isset($user) ? 'Edit' : 'Tambah' ?> User</h4>
        <p class="text-secondary mb-0">
            <?= isset($user) ? 'Perbarui data user' : 'Buat user baru' ?>
        </p>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="dashboard-card">
                <form action="<?= isset($user) ? base_url('acl/users/update/' . $user['id']) : base_url('acl/users/store') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?= old('username', $user['username'] ?? '') ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= old('email', $user['email'] ?? '') ?>" required>
                        </div>
                        
                        <div class="col-md-12">
                            <label for="full_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                   value="<?= old('full_name', $user['full_name'] ?? '') ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="password" class="form-label">
                                Password <?= isset($user) ? '<small class="text-secondary">(Kosongkan jika tidak ingin mengubah)</small>' : '<span class="text-danger">*</span>' ?>
                            </label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   <?= isset($user) ? '' : 'required' ?>>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Telepon</label>
                            <input type="text" class="form-control" id="phone" name="phone" 
                                   value="<?= old('phone', $user['phone'] ?? '') ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="group_id" class="form-label">Group <span class="text-danger">*</span></label>
                            <select class="form-select" id="group_id" name="group_id" required>
                                <option value="">Pilih Group</option>
                                <?php if (isset($groups)): ?>
                                    <?php foreach ($groups as $group): ?>
                                    <option value="<?= $group['id'] ?>" 
                                        <?= old('group_id', $user['group_id'] ?? '') == $group['id'] ? 'selected' : '' ?>>
                                        <?= esc($group['group_name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="is_active" class="form-label">Status</label>
                            <select class="form-select" id="is_active" name="is_active">
                                <option value="1" <?= old('is_active', $user['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>Aktif</option>
                                <option value="0" <?= old('is_active', $user['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Simpan
                        </button>
                        <a href="<?= base_url('acl/users') ?>" class="btn btn-secondary">
                            <i class="bi bi-x-lg me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="dashboard-card">
                <h6 class="mb-3"><i class="bi bi-info-circle me-2"></i>Informasi</h6>
                <ul class="list-unstyled text-secondary small mb-0">
                    <li class="mb-2"><i class="bi bi-dot me-1"></i>Username harus unik dan minimal 3 karakter</li>
                    <li class="mb-2"><i class="bi bi-dot me-1"></i>Email harus valid dan unik</li>
                    <li class="mb-2"><i class="bi bi-dot me-1"></i>Password minimal 6 karakter</li>
                    <li class="mb-2"><i class="bi bi-dot me-1"></i>Group menentukan hak akses user</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
