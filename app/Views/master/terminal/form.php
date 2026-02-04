<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="<?= base_url('master/terminal') ?>">Terminal</a></li>
                <li class="breadcrumb-item active"><?= isset($terminal) ? 'Edit' : 'Tambah' ?> Terminal</li>
            </ol>
        </nav>
        <h4 class="mb-1"><?= isset($terminal) ? 'Edit' : 'Tambah' ?> Terminal</h4>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="dashboard-card">
                <form action="<?= isset($terminal) ? base_url('master/terminal/update/' . $terminal['id']) : base_url('master/terminal/store') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="terminal_code" class="form-label">Kode Terminal <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="terminal_code" name="terminal_code" 
                                   value="<?= old('terminal_code', $terminal['terminal_code'] ?? '') ?>" 
                                   required style="text-transform: uppercase;">
                        </div>
                        
                        <div class="col-md-8">
                            <label for="terminal_name" class="form-label">Nama Terminal <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="terminal_name" name="terminal_name" 
                                   value="<?= old('terminal_name', $terminal['terminal_name'] ?? '') ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="regional" class="form-label">Regional</label>
                            <select class="form-select" id="regional" name="regional">
                                <option value="">Pilih Regional</option>
                                <option value="Regional 1" <?= old('regional', $terminal['regional'] ?? '') == 'Regional 1' ? 'selected' : '' ?>>Regional 1</option>
                                <option value="Regional 2" <?= old('regional', $terminal['regional'] ?? '') == 'Regional 2' ? 'selected' : '' ?>>Regional 2</option>
                                <option value="Regional 3" <?= old('regional', $terminal['regional'] ?? '') == 'Regional 3' ? 'selected' : '' ?>>Regional 3</option>
                                <option value="Regional 4" <?= old('regional', $terminal['regional'] ?? '') == 'Regional 4' ? 'selected' : '' ?>>Regional 4</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="is_active" class="form-label">Status</label>
                            <select class="form-select" id="is_active" name="is_active">
                                <option value="1" <?= old('is_active', $terminal['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>Aktif</option>
                                <option value="0" <?= old('is_active', $terminal['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>Nonaktif</option>
                            </select>
                        </div>
                        
                        <div class="col-12">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?= old('address', $terminal['address'] ?? '') ?></textarea>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Simpan
                        </button>
                        <a href="<?= base_url('master/terminal') ?>" class="btn btn-secondary">
                            <i class="bi bi-x-lg me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
