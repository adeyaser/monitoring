<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Data Terminal</h4>
            <p class="text-secondary mb-0">Kelola data terminal/pelabuhan</p>
        </div>
        <a href="<?= base_url('master/terminal/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Tambah Terminal
        </a>
    </div>
    
    <div class="dashboard-card">
        <div class="table-responsive">
            <table class="table table-dark table-striped table-hover" id="terminalTable">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Kode</th>
                        <th>Nama Terminal</th>
                        <th>Regional</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($terminals) && !empty($terminals)): ?>
                        <?php foreach ($terminals as $index => $terminal): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><code><?= esc($terminal['terminal_code']) ?></code></td>
                            <td><?= esc($terminal['terminal_name']) ?></td>
                            <td><?= esc($terminal['regional'] ?? '-') ?></td>
                            <td><?= esc($terminal['address'] ?? '-') ?></td>
                            <td>
                                <?php if ($terminal['is_active']): ?>
                                    <span class="badge bg-success-subtle text-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= base_url('master/terminal/edit/' . $terminal['id']) ?>" 
                                       class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-delete" 
                                            data-url="<?= base_url('master/terminal/delete/' . $terminal['id']) ?>"
                                            data-name="<?= esc($terminal['terminal_name']) ?>"
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#terminalTable').DataTable(App.dataTableDefaults);
});
</script>
<?= $this->endSection() ?>
