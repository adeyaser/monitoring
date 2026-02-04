<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="<?= base_url('acl/groups') ?>">Groups</a></li>
                <li class="breadcrumb-item active"><?= isset($group) ? 'Edit' : 'Tambah' ?> Group</li>
            </ol>
        </nav>
        <h4 class="mb-1"><?= isset($group) ? 'Edit' : 'Tambah' ?> Group</h4>
        <p class="text-secondary mb-0">
            <?= isset($group) ? 'Perbarui data group' : 'Buat group baru' ?>
        </p>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="dashboard-card">
                <form action="<?= isset($group) ? base_url('acl/groups/update/' . $group['id']) : base_url('acl/groups/store') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="group_name" class="form-label">Nama Group <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="group_name" name="group_name" 
                                   value="<?= old('group_name', $group['group_name'] ?? '') ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="group_code" class="form-label">Kode Group <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="group_code" name="group_code" 
                                   value="<?= old('group_code', $group['group_code'] ?? '') ?>" required
                                   pattern="[a-zA-Z0-9_-]+"
                                   title="Hanya huruf, angka, underscore dan dash">
                        </div>
                        
                        <div class="col-12">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?= old('description', $group['description'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="is_active" class="form-label">Status</label>
                            <select class="form-select" id="is_active" name="is_active">
                                <option value="1" <?= old('is_active', $group['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>Aktif</option>
                                <option value="0" <?= old('is_active', $group['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Simpan
                        </button>
                        <a href="<?= base_url('acl/groups') ?>" class="btn btn-secondary">
                            <i class="bi bi-x-lg me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
