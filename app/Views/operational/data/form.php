<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="<?= base_url('operational/data') ?>">Data Operasional</a></li>
                <li class="breadcrumb-item active"><?= isset($operation) ? 'Edit' : 'Input' ?> Data</li>
            </ol>
        </nav>
        <h4 class="mb-1"><?= isset($operation) ? 'Edit' : 'Input' ?> Data Operasional</h4>
    </div>
    
    <div class="row">
        <div class="col-lg-10">
            <div class="dashboard-card">
                <form action="<?= isset($operation) ? base_url('operational/data/update/' . $operation['id']) : base_url('operational/data/store') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <!-- Header Info -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="terminal_id" class="form-label">Terminal <span class="text-danger">*</span></label>
                            <select class="form-select" id="terminal_id" name="terminal_id" required <?= isset($operation) ? 'disabled' : '' ?>>
                                <option value="">Pilih Terminal</option>
                                <?php if (isset($terminals)): ?>
                                    <?php foreach ($terminals as $terminal): ?>
                                    <option value="<?= $terminal['id'] ?>" 
                                        <?= old('terminal_id', $operation['terminal_id'] ?? '') == $terminal['id'] ? 'selected' : '' ?>>
                                        <?= esc($terminal['terminal_code']) ?> - <?= esc($terminal['terminal_name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="report_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="report_date" name="report_date" 
                                   value="<?= old('report_date', $operation['report_date'] ?? date('Y-m-d')) ?>" 
                                   required <?= isset($operation) ? 'readonly' : '' ?>>
                        </div>
                        <div class="col-md-4">
                            <label for="yor_standar" class="form-label">YOR Standar (%)</label>
                            <input type="number" class="form-control" id="yor_standar" name="yor_standar" 
                                   value="<?= old('yor_standar', $operation['yor_standar'] ?? 65) ?>" step="0.01">
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    <h6 class="text-primary mb-3"><i class="bi bi-box-seam me-2"></i>Data Container</h6>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label for="container_cy" class="form-label">Container CY</label>
                            <input type="number" class="form-control" id="container_cy" name="container_cy" 
                                   value="<?= old('container_cy', $operation['container_cy'] ?? 0) ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="kapasitas_harian_cy" class="form-label">Kapasitas Harian CY</label>
                            <input type="number" class="form-control" id="kapasitas_harian_cy" name="kapasitas_harian_cy" 
                                   value="<?= old('kapasitas_harian_cy', $operation['kapasitas_harian_cy'] ?? 0) ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="container_longstay_3" class="form-label">Longstay > 3 Hari</label>
                            <input type="number" class="form-control" id="container_longstay_3" name="container_longstay_3" 
                                   value="<?= old('container_longstay_3', $operation['container_longstay_3'] ?? 0) ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="container_longstay_30" class="form-label">Longstay > 30 Hari</label>
                            <input type="number" class="form-control" id="container_longstay_30" name="container_longstay_30" 
                                   value="<?= old('container_longstay_30', $operation['container_longstay_30'] ?? 0) ?>">
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    <h6 class="text-primary mb-3"><i class="bi bi-cursor me-2"></i>Data Kapal & Aktivitas</h6>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="jumlah_kapal" class="form-label">Jumlah Kapal</label>
                            <input type="number" class="form-control" id="jumlah_kapal" name="jumlah_kapal" 
                                   value="<?= old('jumlah_kapal', $operation['jumlah_kapal'] ?? 0) ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="bongkar_muat" class="form-label">Bongkar/Muat</label>
                            <input type="number" class="form-control" id="bongkar_muat" name="bongkar_muat" 
                                   value="<?= old('bongkar_muat', $operation['bongkar_muat'] ?? 0) ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="param_receiving_delivery" class="form-label">Receiving/Delivery</label>
                            <input type="number" class="form-control" id="param_receiving_delivery" name="param_receiving_delivery" 
                                   value="<?= old('param_receiving_delivery', $operation['param_receiving_delivery'] ?? 0) ?>">
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    <h6 class="text-primary mb-3"><i class="bi bi-truck me-2"></i>Data Gatepass</h6>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="gatepass_total" class="form-label">Total Gatepass</label>
                            <input type="number" class="form-control" id="gatepass_total" name="gatepass_total" 
                                   value="<?= old('gatepass_total', $operation['gatepass_total'] ?? 0) ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="gatepass_sudah" class="form-label">Gatepass Sudah</label>
                            <input type="number" class="form-control" id="gatepass_sudah" name="gatepass_sudah" 
                                   value="<?= old('gatepass_sudah', $operation['gatepass_sudah'] ?? 0) ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="gatepass_belum" class="form-label">Gatepass Belum</label>
                            <input type="number" class="form-control" id="gatepass_belum" name="gatepass_belum" 
                                   value="<?= old('gatepass_belum', $operation['gatepass_belum'] ?? 0) ?>">
                        </div>
                    </div>
                    
                    <!-- YOR Preview -->
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-info-circle fs-4"></i>
                            <div>
                                <strong>Perhitungan YOR Eksisting:</strong>
                                <span id="yorPreview">(Container CY / Kapasitas Harian) × 100</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Simpan
                        </button>
                        <a href="<?= base_url('operational/data') ?>" class="btn btn-secondary">
                            <i class="bi bi-x-lg me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Calculate YOR preview on change
function calculateYOR() {
    const containerCy = parseInt(document.getElementById('container_cy').value) || 0;
    const kapasitas = parseInt(document.getElementById('kapasitas_harian_cy').value) || 0;
    
    if (kapasitas > 0) {
        const yor = (containerCy / kapasitas) * 100;
        document.getElementById('yorPreview').textContent = 
            `${containerCy.toLocaleString()} / ${kapasitas.toLocaleString()} × 100 = ${yor.toFixed(2)}%`;
    } else {
        document.getElementById('yorPreview').textContent = '(Container CY / Kapasitas Harian) × 100';
    }
}

document.getElementById('container_cy').addEventListener('input', calculateYOR);
document.getElementById('kapasitas_harian_cy').addEventListener('input', calculateYOR);

// Initial calculation
calculateYOR();
</script>
<?= $this->endSection() ?>
