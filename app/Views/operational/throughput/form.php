<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="<?= base_url('operational/throughput') ?>">Throughput</a></li>
                <li class="breadcrumb-item active">Input Data</li>
            </ol>
        </nav>
        <h4 class="mb-1">Input Data Throughput</h4>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="dashboard-card">
                <form action="<?= base_url('operational/throughput/store') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="terminal_id" class="form-label">Terminal</label>
                            <select class="form-select" id="terminal_id" name="terminal_id">
                                <option value="">Total (Semua Terminal)</option>
                                <?php foreach ($terminals as $terminal): ?>
                                <option value="<?= $terminal['id'] ?>">
                                    <?= esc($terminal['terminal_code']) ?> - <?= esc($terminal['terminal_name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="report_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="report_date" name="report_date" 
                                   value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="throughput_teus" class="form-label">Throughput (TEUs)</label>
                            <input type="number" class="form-control" id="throughput_teus" name="throughput_teus" 
                                   value="0" min="0">
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    <h6 class="text-primary mb-3"><i class="bi bi-box me-2"></i>Container Movement</h6>
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="export_box" class="form-label">Export (Box)</label>
                            <input type="number" class="form-control" id="export_box" name="export_box" 
                                   value="0" min="0">
                        </div>
                        <div class="col-md-4">
                            <label for="import_box" class="form-label">Import (Box)</label>
                            <input type="number" class="form-control" id="import_box" name="import_box" 
                                   value="0" min="0">
                        </div>
                        <div class="col-md-4">
                            <label for="transship_box" class="form-label">Transshipment (Box)</label>
                            <input type="number" class="form-control" id="transship_box" name="transship_box" 
                                   value="0" min="0">
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Simpan
                        </button>
                        <a href="<?= base_url('operational/throughput') ?>" class="btn btn-secondary">
                            <i class="bi bi-x-lg me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
