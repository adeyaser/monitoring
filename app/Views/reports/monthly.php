<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h4 class="mb-1">Laporan Bulanan</h4>
            <p class="text-secondary mb-0">Rekapitulasi data operasional per bulan</p>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <form action="" method="GET" class="d-flex gap-2 flex-wrap">
                <select class="form-select" name="month" style="min-width: 140px;">
                    <?php foreach ($months as $value => $name): ?>
                    <option value="<?= $value ?>" <?= $selectedMonth == $value ? 'selected' : '' ?>>
                        <?= $name ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <select class="form-select" name="year" style="min-width: 100px;">
                    <?php foreach ($years as $year): ?>
                    <option value="<?= $year ?>" <?= $selectedYear == $year ? 'selected' : '' ?>>
                        <?= $year ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
            </form>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Summary Card -->
        <div class="col-12">
            <div class="dashboard-card">
                <h6 class="mb-4">
                    <i class="bi bi-calendar-month me-2"></i>
                    Periode: <?= $months[$selectedMonth] ?> <?= $selectedYear ?>
                </h6>
                
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Laporan bulanan akan menampilkan agregasi data operasional harian per bulan.
                    Fitur ini akan diimplementasikan dengan data agregat dari database.
                </div>
            </div>
        </div>
        
        <!-- Terminal Summary -->
        <div class="col-lg-6">
            <div class="dashboard-card h-100">
                <h6 class="mb-4"><i class="bi bi-building me-2"></i>Daftar Terminal</h6>
                <div class="table-responsive">
                    <table class="table table-dark table-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Terminal</th>
                                <th>Regional</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($terminals as $index => $terminal): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= esc($terminal['terminal_name']) ?></td>
                                <td><?= esc($terminal['regional'] ?? '-') ?></td>
                                <td>
                                    <?php if ($terminal['is_active']): ?>
                                    <span class="badge bg-success-subtle text-success">Aktif</span>
                                    <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Chart Placeholder -->
        <div class="col-lg-6">
            <div class="dashboard-card h-100">
                <h6 class="mb-4"><i class="bi bi-bar-chart me-2"></i>Grafik Throughput Bulanan</h6>
                <div class="d-flex align-items-center justify-content-center" style="height: 250px;">
                    <div class="text-center text-secondary">
                        <i class="bi bi-bar-chart-line" style="font-size: 48px;"></i>
                        <p class="mt-3 mb-0">Grafik akan ditampilkan di sini</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
