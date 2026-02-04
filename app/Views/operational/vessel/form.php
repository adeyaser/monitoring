<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="<?= base_url('operational/vessel') ?>">Vessel Schedule</a></li>
                <li class="breadcrumb-item active"><?= isset($vessel) ? 'Edit' : 'Tambah' ?></li>
            </ol>
        </nav>
        <h4 class="mb-1"><?= isset($vessel) ? 'Edit' : 'Tambah' ?> Vessel Schedule</h4>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="dashboard-card">
                <form action="<?= isset($vessel) ? base_url('operational/vessel/update/' . $vessel['id']) : base_url('operational/vessel/store') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="vessel_name" class="form-label">Nama Kapal <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="vessel_name" name="vessel_name" 
                                   value="<?= old('vessel_name', $vessel['vessel_name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="vessel_code" class="form-label">Kode Kapal</label>
                            <input type="text" class="form-control" id="vessel_code" name="vessel_code" 
                                   value="<?= old('vessel_code', $vessel['vessel_code'] ?? '') ?>" 
                                   placeholder="e.g. EG2024">
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="terminal_id" class="form-label">Terminal <span class="text-danger">*</span></label>
                            <select class="form-select" id="terminal_id" name="terminal_id" required>
                                <option value="">Pilih Terminal</option>
                                <?php foreach ($terminals as $terminal): ?>
                                <option value="<?= $terminal['id'] ?>" 
                                    <?= old('terminal_id', $vessel['terminal_id'] ?? '') == $terminal['id'] ? 'selected' : '' ?>>
                                    <?= esc($terminal['terminal_code']) ?> - <?= esc($terminal['terminal_name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="berth_position" class="form-label">Posisi Berth</label>
                            <select class="form-select" id="berth_position" name="berth_position">
                                <option value="">Pilih Posisi Berth</option>
                                <?php 
                                $berths = [
                                    'Berth 01', 'Berth 02', 'Berth 03', 'Berth 04', 'Berth 05',
                                    'Berth East', 'Berth West', 'Berth North', 'Berth South'
                                ];
                                $selectedBerth = old('berth_position', $vessel['berth_position'] ?? '');
                                foreach($berths as $berth): 
                                ?>
                                <option value="<?= $berth ?>" <?= $selectedBerth == $berth ? 'selected' : '' ?>>
                                    <?= $berth ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="arrival_date" class="form-label">Waktu Tiba</label>
                            <input type="datetime-local" class="form-control" id="arrival_date" name="arrival_date" 
                                   value="<?= old('arrival_date', $vessel['arrival_date'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="departure_date" class="form-label">Waktu Berangkat</label>
                            <input type="datetime-local" class="form-control" id="departure_date" name="departure_date" 
                                   value="<?= old('departure_date', $vessel['departure_date'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="SCHEDULED" <?= old('status', $vessel['status'] ?? '') == 'SCHEDULED' ? 'selected' : '' ?>>Scheduled</option>
                                <option value="BERTHING" <?= old('status', $vessel['status'] ?? '') == 'BERTHING' ? 'selected' : '' ?>>Berthing</option>
                                <option value="LOADING" <?= old('status', $vessel['status'] ?? '') == 'LOADING' ? 'selected' : '' ?>>Loading</option>
                                <option value="UNLOADING" <?= old('status', $vessel['status'] ?? '') == 'UNLOADING' ? 'selected' : '' ?>>Unloading</option>
                                <option value="DEPARTED" <?= old('status', $vessel['status'] ?? '') == 'DEPARTED' ? 'selected' : '' ?>>Departed</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="cargo_type" class="form-label">Jenis Cargo</label>
                            <select class="form-select" id="cargo_type" name="cargo_type">
                                <option value="">Pilih Jenis Cargo</option>
                                <?php 
                                $cargos = ['Container', 'Liquid Bulk', 'Dry Bulk', 'General Cargo', 'Vehicle', 'Others'];
                                $selectedCargo = old('cargo_type', $vessel['cargo_type'] ?? 'Container');
                                foreach($cargos as $cargo): 
                                ?>
                                <option value="<?= $cargo ?>" <?= $selectedCargo == $cargo ? 'selected' : '' ?>>
                                    <?= $cargo ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Simpan
                        </button>
                        <a href="<?= base_url('operational/vessel') ?>" class="btn btn-secondary">
                            <i class="bi bi-x-lg me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
