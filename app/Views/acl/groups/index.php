<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Manajemen Group</h4>
            <p class="text-secondary mb-0">Kelola data group/role pengguna</p>
        </div>
        <a href="<?= base_url('acl/groups/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Tambah Group
        </a>
    </div>
    
    <div class="row g-4">
        <?php if (isset($groups) && !empty($groups)): ?>
            <?php foreach ($groups as $group): ?>
            <div class="col-lg-4 col-md-6">
                <div class="dashboard-card group-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="group-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-icon" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="<?= base_url('acl/groups/edit/' . $group['id']) ?>">
                                        <i class="bi bi-pencil me-2"></i>Edit
                                    </a>
                                </li>
                                <li>
                                    <button class="dropdown-item text-danger btn-delete" 
                                            data-url="<?= base_url('acl/groups/delete/' . $group['id']) ?>"
                                            data-name="<?= esc($group['group_name']) ?>">
                                        <i class="bi bi-trash me-2"></i>Hapus
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <h5 class="mb-1"><?= esc($group['group_name']) ?></h5>
                    <p class="text-secondary small mb-3">
                        <code><?= esc($group['group_code']) ?></code>
                    </p>
                    
                    <?php if (!empty($group['description'])): ?>
                    <p class="text-secondary small mb-3"><?= esc($group['description']) ?></p>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-primary-subtle text-primary">
                            <i class="bi bi-people me-1"></i>
                            <?= $group['user_count'] ?? 0 ?> Users
                        </span>
                        <?php if ($group['is_active']): ?>
                            <span class="badge bg-success-subtle text-success">Aktif</span>
                        <?php else: ?>
                            <span class="badge bg-danger-subtle text-danger">Nonaktif</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="dashboard-card text-center py-5">
                    <i class="bi bi-inbox display-1 text-secondary mb-3"></i>
                    <h5 class="text-secondary">Belum ada data group</h5>
                    <p class="text-muted mb-4">Klik tombol "Tambah Group" untuk membuat group baru</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.group-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.group-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
}
.group-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
}
</style>
<?= $this->endSection() ?>
