<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="<?= base_url('acl/menus') ?>">Menus</a></li>
                <li class="breadcrumb-item active"><?= isset($menu) ? 'Edit' : 'Tambah' ?> Menu</li>
            </ol>
        </nav>
        <h4 class="mb-1"><?= isset($menu) ? 'Edit' : 'Tambah' ?> Menu</h4>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="dashboard-card">
                <form action="<?= isset($menu) ? base_url('acl/menus/update/' . $menu['id']) : base_url('acl/menus/store') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="menu_name" class="form-label">Nama Menu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="menu_name" name="menu_name" 
                                   value="<?= old('menu_name', $menu['menu_name'] ?? '') ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="menu_code" class="form-label">Kode Menu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="menu_code" name="menu_code" 
                                   value="<?= old('menu_code', $menu['menu_code'] ?? '') ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="menu_url" class="form-label">URL</label>
                            <input type="text" class="form-control" id="menu_url" name="menu_url" 
                                   value="<?= old('menu_url', $menu['menu_url'] ?? '') ?>" placeholder="/path/to/page">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="menu_icon" class="form-label">Icon (Bootstrap Icons)</label>
                            <input type="text" class="form-control" id="menu_icon" name="menu_icon" 
                                   value="<?= old('menu_icon', $menu['menu_icon'] ?? '') ?>" placeholder="bi-house">
                        </div>
                        
                        <div class="col-md-4">
                            <label for="parent_id" class="form-label">Parent Menu</label>
                            <select class="form-select" id="parent_id" name="parent_id">
                                <option value="">-- Tidak Ada --</option>
                                <?php if (isset($parentMenus)): ?>
                                    <?php foreach ($parentMenus as $parent): ?>
                                    <option value="<?= $parent['id'] ?>" 
                                        <?= old('parent_id', $menu['parent_id'] ?? '') == $parent['id'] ? 'selected' : '' ?>>
                                        <?= esc($parent['menu_name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="menu_order" class="form-label">Urutan</label>
                            <input type="number" class="form-control" id="menu_order" name="menu_order" 
                                   value="<?= old('menu_order', $menu['menu_order'] ?? 0) ?>" min="0">
                        </div>
                        
                        <div class="col-md-4">
                            <label for="is_active" class="form-label">Status</label>
                            <select class="form-select" id="is_active" name="is_active">
                                <option value="1" <?= old('is_active', $menu['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>Aktif</option>
                                <option value="0" <?= old('is_active', $menu['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Simpan
                        </button>
                        <a href="<?= base_url('acl/menus') ?>" class="btn btn-secondary">
                            <i class="bi bi-x-lg me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="dashboard-card">
                <h6 class="mb-3"><i class="bi bi-bookmark me-2"></i>Icon Reference</h6>
                <p class="text-secondary small">Gunakan class icon dari <a href="https://icons.getbootstrap.com/" target="_blank" class="text-primary">Bootstrap Icons</a></p>
                <div class="icon-preview mt-3">
                    <div class="preview-icon">
                        <i class="bi bi-house"></i>
                        <span>bi-house</span>
                    </div>
                    <div class="preview-icon">
                        <i class="bi bi-gear"></i>
                        <span>bi-gear</span>
                    </div>
                    <div class="preview-icon">
                        <i class="bi bi-people"></i>
                        <span>bi-people</span>
                    </div>
                    <div class="preview-icon">
                        <i class="bi bi-file-text"></i>
                        <span>bi-file-text</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.icon-preview {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}
.preview-icon {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 12px;
    background: var(--dark-200);
    border-radius: 8px;
    font-size: 24px;
}
.preview-icon span {
    font-size: 10px;
    color: var(--text-secondary);
    margin-top: 8px;
}
</style>
<?= $this->endSection() ?>
