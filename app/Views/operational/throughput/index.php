<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h4 class="mb-1">Data Throughput</h4>
            <p class="text-secondary mb-0">Container throughput per terminal</p>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <form action="" method="GET" class="d-flex gap-2">
                <input type="date" class="form-control" name="date" value="<?= esc($selectedDate) ?>">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-funnel"></i>
                </button>
            </form>
            <a href="<?= base_url('operational/throughput/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>Input Data
            </a>
        </div>
    </div>
    
    <!-- Overall Summary -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-secondary small mb-1">Total TEUs</p>
                        <h3 class="mb-0"><?= number_format($overall['total_teus'] ?? 0) ?></h3>
                    </div>
                    <div class="stat-icon bg-primary-subtle">
                        <i class="bi bi-box-seam text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-secondary small mb-1">Export</p>
                        <h3 class="mb-0"><?= number_format($overall['total_export'] ?? 0) ?></h3>
                    </div>
                    <div class="stat-icon bg-success-subtle">
                        <i class="bi bi-arrow-up-right text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-secondary small mb-1">Import</p>
                        <h3 class="mb-0"><?= number_format($overall['total_import'] ?? 0) ?></h3>
                    </div>
                    <div class="stat-icon bg-info-subtle">
                        <i class="bi bi-arrow-down-left text-info"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-secondary small mb-1">Transshipment</p>
                        <h3 class="mb-0"><?= number_format($overall['total_transship'] ?? 0) ?></h3>
                    </div>
                    <div class="stat-icon bg-warning-subtle">
                        <i class="bi bi-arrow-left-right text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="dashboard-card">
        <div class="table-responsive">
            <table class="table table-dark table-striped table-hover" id="throughputTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Terminal</th>
                        <th class="text-end">TEUs</th>
                        <th class="text-end">Export (Box)</th>
                        <th class="text-end">Import (Box)</th>
                        <th class="text-end">Transship (Box)</th>
                        <th class="text-end">Total Box</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($throughputData)): ?>
                        <?php foreach ($throughputData as $index => $tp): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($tp['terminal_name'] ?? 'Total') ?></td>
                            <td class="text-end"><?= number_format($tp['throughput_teus']) ?></td>
                            <td class="text-end"><?= number_format($tp['export_box']) ?></td>
                            <td class="text-end"><?= number_format($tp['import_box']) ?></td>
                            <td class="text-end"><?= number_format($tp['transship_box']) ?></td>
                            <td class="text-end"><?= number_format($tp['export_box'] + $tp['import_box'] + $tp['transship_box']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-secondary">Tidak ada data throughput</td>
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
    $('#throughputTable').DataTable(App.dataTableDefaults);
});
</script>
<?= $this->endSection() ?>
