<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h4 class="mb-1">Data Operasional</h4>
            <p class="text-secondary mb-0">Data operasional harian terminal</p>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <form action="" method="GET" class="d-flex gap-2">
                <input type="date" class="form-control" name="date" value="<?= esc($selectedDate) ?>">
                <button type="submit" class="btn btn-outline-primary" title="Filter Tanggal">
                    <i class="bi bi-funnel"></i>
                </button>
            </form>
            <div class="vr"></div>
            <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#importModal" title="Import Excel">
                <i class="bi bi-upload"></i>
            </button>
            <button type="button" id="btnExportExcel" class="btn btn-outline-success" title="Export Excel">
                <i class="bi bi-file-earmark-excel"></i>
            </button>
            <div class="vr"></div>
            <a href="<?= base_url('operational/data/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>Input Data
            </a>
        </div>
    </div>
    
    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-secondary small mb-1">Total Terminal</p>
                        <h3 class="mb-0" id="totalTerminal"><?= count($operations) ?></h3>
                    </div>
                    <div class="stat-icon bg-primary-subtle">
                        <i class="bi bi-building text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-secondary small mb-1">Total Container CY</p>
                        <h3 class="mb-0" id="totalContainer">
                            <?= number_format(array_sum(array_column($operations, 'container_cy'))) ?>
                        </h3>
                    </div>
                    <div class="stat-icon bg-success-subtle">
                        <i class="bi bi-box-seam text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-secondary small mb-1">Total Gatepass</p>
                        <h3 class="mb-0" id="totalGatepass">
                            <?= number_format(array_sum(array_column($operations, 'gatepass_total'))) ?>
                        </h3>
                    </div>
                    <div class="stat-icon bg-warning-subtle">
                        <i class="bi bi-truck text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-secondary small mb-1">Jumlah Kapal</p>
                        <h3 class="mb-0" id="totalVessel">
                            <?= array_sum(array_column($operations, 'jumlah_kapal')) ?>
                        </h3>
                    </div>
                    <div class="stat-icon bg-info-subtle">
                        <i class="bi bi-cursor text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="dashboard-card">
        <div class="table-responsive">
            <table class="table table-dark table-striped table-hover" id="operationalTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Terminal</th>
                        <th>YOR Standar</th>
                        <th>YOR Eksisting</th>
                        <th>Container CY</th>
                        <th>Longstay > 3 Hari</th>
                        <th>Longstay > 30 Hari</th>
                        <th>Kapasitas</th>
                        <th>Jml Kapal</th>
                        <th>Bongkar/Muat</th>
                        <th>Gatepass</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($operations)): ?>
                        <?php foreach ($operations as $index => $op): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($op['terminal_name'] ?? $op['terminal_id']) ?></td>
                            <td class="text-center"><?= $op['yor_standar'] ?>%</td>
                            <td class="text-center">
                                <?php if ($op['yor_eksisting']): ?>
                                    <?= number_format($op['yor_eksisting'], 1) ?>%
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="text-end"><?= number_format($op['container_cy']) ?></td>
                            <td class="text-end"><?= number_format($op['container_longstay_3']) ?></td>
                            <td class="text-end"><?= number_format($op['container_longstay_30']) ?></td>
                            <td class="text-end"><?= number_format($op['kapasitas_harian_cy']) ?></td>
                            <td class="text-center"><?= $op['jumlah_kapal'] ?></td>
                            <td class="text-end"><?= number_format($op['bongkar_muat']) ?></td>
                            <td class="text-end"><?= number_format($op['gatepass_total']) ?></td>
                            <td class="text-center">
                                <?php 
                                $statusClass = match($op['status_yor']) {
                                    'AMAN' => 'status-aman',
                                    'WARNING' => 'status-warning',
                                    'CRITICAL' => 'status-critical',
                                    default => 'status-aman'
                                };
                                ?>
                                <span class="status-badge <?= $statusClass ?>"><?= $op['status_yor'] ?></span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= base_url('operational/data/edit/' . $op['id']) ?>" 
                                       class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-delete" 
                                            data-url="<?= base_url('operational/data/delete/' . $op['id']) ?>"
                                            data-name="data operasional <?= esc($op['terminal_name'] ?? '') ?>"
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

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Data Operasional</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Tanggal Kegiatan</label>
                    <input type="date" class="form-control" id="importDate" value="<?= $selectedDate ?>">
                    <div class="form-text">Tanggal ini akan digunakan jika di Excel tidak ada kolom Tanggal.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Upload File Excel (.xlsx)</label>
                    <input type="file" class="form-control" id="importFile" accept=".xlsx, .xls">
                    <div class="d-flex justify-content-between mt-2">
                        <div class="form-text">Gunakan format file sesuai Template.</div>
                        <a href="#" id="btnDownloadTemplate" class="text-decoration-none small">
                            <i class="bi bi-download me-1"></i>Download Template (.xlsx)
                        </a>
                    </div>
                </div>
                <div class="progress mb-3 d-none" id="importProgressWrapper">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%">Sedang Memproses...</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnProcessImport">
                    <i class="bi bi-cloud-upload me-1"></i>Proses Import
                </button>
            </div>
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
    $('#operationalTable').DataTable({
        ...App.dataTableDefaults,
        pageLength: 15,
        scrollX: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip'
    });

    // --- 1. PREPARE DATA FOR EXPORT ---
    <?php
    $jsExportData = [];
    foreach ($operations as $index => $op) {
        $jsExportData[] = [
            $index + 1,
            $op['report_date'], // Add Date Column
            $op['terminal_name'] ?? $op['terminal_id'],
            $op['yor_standar'] . '%',
            ($op['yor_eksisting'] ? number_format($op['yor_eksisting'], 1) . '%' : '-'),
            $op['container_cy'],
            $op['container_longstay_3'],
            $op['container_longstay_30'],
            $op['kapasitas_harian_cy'],
            $op['jumlah_kapal'],
            $op['bongkar_muat'],
            $op['gatepass_total'],
            $op['status_yor']
        ];
    }
    ?>
    const exportData = <?= json_encode($jsExportData) ?>;
    const exportHeaders = [
        "No", "Tanggal", "Terminal", "YOR Standar", "YOR Eksisting", 
        "Container CY", "Longstay > 3", "Longstay > 30", 
        "Kapasitas Harian", "Jumlah Kapal", "Bongkar/Muat", 
        "Gatepass", "Status"
    ];

    // --- 2. EXPORT HANDLER ---
    $('#btnExportExcel').on('click', function(e) {
        e.preventDefault();
        try {
            if (typeof App === 'undefined' || typeof App.exportDataToExcel !== 'function') {
                throw new Error('Module App belum siap');
            }
            if (exportData.length === 0) {
                App.showToast('Tidak ada data untuk diexport', 'warning');
                return;
            }
            App.exportDataToExcel(exportData, exportHeaders, 'Data_Operasional_<?= $selectedDate ?>.xlsx', 'Data Operasional');
        } catch (err) {
            console.error(err);
            alert('Export gagal: ' + err.message);
        }
    });

    // --- 3. DOWNLOAD TEMPLATE HANDLER (Client Side) ---
    $('#btnDownloadTemplate').on('click', function(e) {
        e.preventDefault();
        
        const templateData = [
            {
                "No": 1,
                "Tanggal": "2026-02-04",
                "Terminal": "JICT",
                "YOR Standar": "65%",
                "YOR Eksisting": "45%",
                "Container CY": 15500,
                "Longstay > 3": 0,
                "Longstay > 30": 0,
                "Kapasitas Harian": 25000,
                "Jumlah Kapal": 5,
                "Bongkar/Muat": 1200,
                "Gatepass": 800,
                "Status": "AMAN"
            }
        ];

        try {
            if (typeof XLSX === 'undefined') throw new Error('Library XLSX belum siap.');
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.json_to_sheet(templateData);
            const wscols = Object.keys(templateData[0]).map(key => ({wch: Math.max(key.length, 10) + 2}));
            ws['!cols'] = wscols;
            XLSX.utils.book_append_sheet(wb, ws, "Template");
            XLSX.writeFile(wb, "Template_Data_Operasional.xlsx");
        } catch (err) {
            console.error(err);
            alert('Gagal download template: ' + err.message);
        }
    });

    // --- 4. IMPORT PROCESS HANDLER ---
    $('#btnProcessImport').on('click', function() {
        const fileInput = document.getElementById('importFile');
        const dateInput = document.getElementById('importDate');
        
        if (!fileInput.files.length) {
            alert('Pilih file terlebih dahulu!');
            return;
        }
        
        if (!dateInput.value) {
            alert('Pilih tanggal kegiatan!');
            return;
        }

        const btn = $(this);
        const originalText = btn.html();
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Processing...');
        $('#importProgressWrapper').removeClass('d-none');
        
        const selectedDate = dateInput.value;

        const file = fileInput.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            try {
                const data = new Uint8Array(e.target.result);
                if (typeof XLSX === 'undefined') throw new Error('Library XLSX belum siap.');
                
                const workbook = XLSX.read(data, {type: 'array'});
                const firstSheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[firstSheetName];
                const jsonData = XLSX.utils.sheet_to_json(worksheet);
                
                if (jsonData.length === 0) throw new Error('File kosong atau format salah');

                // Send to Server
                fetch('<?= base_url('operational/data/import') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        date: selectedDate, // Default date if specific row date is missing
                        data: jsonData
                    })
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        $('#importModal').modal('hide');
                        App.showToast('Data berhasil diimport!', 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        alert('Gagal import: ' + (res.message || 'Unknown error'));
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan saat mengirim data.');
                })
                .finally(() => {
                    btn.prop('disabled', false).html(originalText);
                    $('#importProgressWrapper').addClass('d-none');
                });

            } catch (err) {
                console.error(err);
                alert('Gagal membaca file: ' + err.message);
                btn.prop('disabled', false).html(originalText);
                $('#importProgressWrapper').addClass('d-none');
            }
        };
        
        reader.readAsArrayBuffer(file);
    });
});
</script>
<?= $this->endSection() ?>
