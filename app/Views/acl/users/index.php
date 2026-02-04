<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Manajemen User</h4>
            <p class="text-secondary mb-0">Kelola data user sistem</p>
        </div>
        <a href="<?= base_url('acl/users/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Tambah User
        </a>
    </div>
    
    <div class="dashboard-card">
        <div class="table-responsive">
            <table class="table table-dark table-striped table-hover" id="usersTable">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Group</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($users) && !empty($users)): ?>
                        <?php foreach ($users as $index => $user): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar-sm">
                                        <i class="bi bi-person-circle"></i>
                                    </div>
                                    <span><?= esc($user['username']) ?></span>
                                </div>
                            </td>
                            <td><?= esc($user['full_name']) ?></td>
                            <td><?= esc($user['email']) ?></td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary">
                                    <?= esc($user['group_name'] ?? '-') ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($user['is_active']): ?>
                                    <span class="badge bg-success-subtle text-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : '-' ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= base_url('acl/users/edit/' . $user['id']) ?>" 
                                       class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-delete" 
                                            data-url="<?= base_url('acl/users/delete/' . $user['id']) ?>"
                                            data-name="<?= esc($user['username']) ?>"
                                            title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.user-avatar-sm {
    width: 32px;
    height: 32px;
    background: var(--dark-300);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    color: var(--text-secondary);
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#usersTable').DataTable(App.dataTableDefaults);
});
</script>
<?= $this->endSection() ?>
