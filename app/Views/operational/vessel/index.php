<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h4 class="mb-1">Vessel Schedule</h4>
            <p class="text-secondary mb-0">Jadwal kapal di semua terminal</p>
        </div>
        <a href="<?= base_url('operational/vessel/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Tambah Schedule
        </a>
    </div>
    
    <!-- Vessel Stats -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-secondary small mb-1">Scheduled</p>
                        <h3 class="mb-0"><?= $vesselStats['scheduled'] ?? 0 ?></h3>
                    </div>
                    <div class="stat-icon bg-info-subtle">
                        <i class="bi bi-calendar-event text-info"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-secondary small mb-1">Berthing</p>
                        <h3 class="mb-0"><?= $vesselStats['berthing'] ?? 0 ?></h3>
                    </div>
                    <div class="stat-icon bg-warning-subtle">
                        <i class="bi bi-pause-circle text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-secondary small mb-1">Loading/Unloading</p>
                        <h3 class="mb-0"><?= ($vesselStats['loading'] ?? 0) + ($vesselStats['unloading'] ?? 0) ?></h3>
                    </div>
                    <div class="stat-icon bg-primary-subtle">
                        <i class="bi bi-box-arrow-in-down text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-secondary small mb-1">Total Active</p>
                        <h3 class="mb-0"><?= $vesselStats['total'] ?? 0 ?></h3>
                    </div>
                    <div class="stat-icon bg-success-subtle">
                        <i class="bi bi-cursor text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="dashboard-card">
        <div class="table-responsive">
            <table class="table table-dark table-striped table-hover" id="vesselTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kapal</th>
                        <th>Kode</th>
                        <th>Terminal</th>
                        <th>Berth</th>
                        <th>Arrival</th>
                        <th>Departure</th>
                        <th>Status</th>
                        <th>Cargo</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($vessels)): ?>
                        <?php foreach ($vessels as $index => $vessel): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><strong><?= esc($vessel['vessel_name']) ?></strong></td>
                            <td><code><?= esc($vessel['vessel_code'] ?? '-') ?></code></td>
                            <td><?= esc($vessel['terminal_name'] ?? '-') ?></td>
                            <td><?= esc($vessel['berth_position'] ?? '-') ?></td>
                            <td><?= $vessel['arrival_date'] ? date('d/m/Y H:i', strtotime($vessel['arrival_date'])) : '-' ?></td>
                            <td><?= $vessel['departure_date'] ? date('d/m/Y H:i', strtotime($vessel['departure_date'])) : '-' ?></td>
                            <td>
                                <?php 
                                $statusClass = match($vessel['status']) {
                                    'SCHEDULED' => 'bg-info-subtle text-info',
                                    'BERTHING' => 'bg-warning-subtle text-warning',
                                    'LOADING' => 'bg-primary-subtle text-primary',
                                    'UNLOADING' => 'bg-primary-subtle text-primary',
                                    'DEPARTED' => 'bg-secondary text-white',
                                    default => 'bg-secondary text-white'
                                };
                                ?>
                                <span class="badge <?= $statusClass ?>"><?= $vessel['status'] ?></span>
                            </td>
                            <td><?= esc($vessel['cargo_type'] ?? '-') ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= base_url('operational/vessel/edit/' . $vessel['id']) ?>" 
                                       class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-delete" 
                                            data-url="<?= base_url('operational/vessel/delete/' . $vessel['id']) ?>"
                                            data-name="<?= esc($vessel['vessel_name']) ?>"
                                            title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center text-secondary">Tidak ada data vessel</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#vesselTable').DataTable(App.dataTableDefaults);
});
</script>
<?= $this->endSection() ?>
