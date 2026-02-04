<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Manajemen Menu</h4>
            <p class="text-secondary mb-0">Kelola struktur menu aplikasi</p>
        </div>
        <a href="<?= base_url('acl/menus/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Tambah Menu
        </a>
    </div>
    
    <div class="dashboard-card">
        <div class="menu-tree">
            <?php if (isset($menus) && !empty($menus)): ?>
                <?php foreach ($menus as $menu): ?>
                <div class="menu-item parent">
                    <div class="menu-row">
                        <div class="menu-info">
                            <i class="bi <?= esc($menu['menu_icon'] ?? 'bi-circle') ?> menu-icon"></i>
                            <span class="menu-name"><?= esc($menu['menu_name']) ?></span>
                            <code class="menu-code"><?= esc($menu['menu_code']) ?></code>
                        </div>
                        <div class="menu-meta">
                            <span class="menu-url"><?= esc($menu['menu_url'] ?? '-') ?></span>
                            <?php if ($menu['is_active']): ?>
                                <span class="badge bg-success-subtle text-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-danger-subtle text-danger">Nonaktif</span>
                            <?php endif; ?>
                        </div>
                        <div class="menu-actions">
                            <a href="<?= base_url('acl/menus/edit/' . $menu['id']) ?>" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" 
                                    class="btn btn-sm btn-outline-danger btn-delete" 
                                    data-url="<?= base_url('acl/menus/delete/' . $menu['id']) ?>"
                                    data-name="<?= esc($menu['menu_name']) ?>">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    
                    <?php if (isset($menu['children']) && !empty($menu['children'])): ?>
                    <div class="menu-children">
                        <?php foreach ($menu['children'] as $child): ?>
                        <div class="menu-item child">
                            <div class="menu-row">
                                <div class="menu-info">
                                    <i class="bi <?= esc($child['menu_icon'] ?? 'bi-circle') ?> menu-icon"></i>
                                    <span class="menu-name"><?= esc($child['menu_name']) ?></span>
                                    <code class="menu-code"><?= esc($child['menu_code']) ?></code>
                                </div>
                                <div class="menu-meta">
                                    <span class="menu-url"><?= esc($child['menu_url'] ?? '-') ?></span>
                                    <?php if ($child['is_active']): ?>
                                        <span class="badge bg-success-subtle text-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger">Nonaktif</span>
                                    <?php endif; ?>
                                </div>
                                <div class="menu-actions">
                                    <a href="<?= base_url('acl/menus/edit/' . $child['id']) ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger btn-delete" 
                                            data-url="<?= base_url('acl/menus/delete/' . $child['id']) ?>"
                                            data-name="<?= esc($child['menu_name']) ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-list-ul display-1 text-secondary mb-3"></i>
                    <h5 class="text-secondary">Belum ada menu</h5>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.menu-tree {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.menu-item {
    border-radius: 8px;
    overflow: hidden;
}
.menu-item.parent {
    background: var(--dark-200);
}
.menu-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    gap: 16px;
}
.menu-info {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
}
.menu-icon {
    font-size: 18px;
    color: var(--primary);
    width: 24px;
    text-align: center;
}
.menu-name {
    font-weight: 500;
}
.menu-code {
    font-size: 11px;
    background: var(--dark-300);
    padding: 2px 8px;
    border-radius: 4px;
}
.menu-meta {
    display: flex;
    align-items: center;
    gap: 12px;
}
.menu-url {
    font-size: 12px;
    color: var(--text-secondary);
}
.menu-actions {
    display: flex;
    gap: 4px;
}
.menu-children {
    padding-left: 40px;
    background: var(--dark-100);
    border-top: 1px solid var(--glass-border);
}
.menu-item.child .menu-row {
    padding: 10px 16px;
}
.menu-item.child .menu-icon {
    font-size: 14px;
}
</style>
<?= $this->endSection() ?>
