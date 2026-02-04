<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h4 class="mb-1">Laporan Harian</h4>
            <p class="text-secondary mb-0">Data operasional terminal per tanggal</p>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <form action="" method="GET" class="d-flex gap-2 flex-wrap">
                <input type="date" class="form-control" name="date" value="<?= esc($selectedDate) ?>">
                <select class="form-select" name="terminal_id" style="min-width: 180px;">
                    <option value="">Semua Terminal</option>
                    <?php foreach ($terminals as $terminal): ?>
                    <option value="<?= $terminal['id'] ?>" <?= $selectedTerminal == $terminal['id'] ? 'selected' : '' ?>>
                        <?= esc($terminal['terminal_name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
            </form>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="bi bi-upload me-1"></i>Import
                </button>
                <button type="button" id="btnExportExcel" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
                </button>
            </div>
        </div>
    </div>
    
    <!-- Report Summary -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-secondary small mb-1">Total Terminal</p>
                        <h3 class="mb-0"><?= count($operations) ?></h3>
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
                        <h3 class="mb-0"><?= number_format(array_sum(array_column($operations, 'container_cy'))) ?></h3>
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
                        <p class="text-secondary small mb-1">Status AMAN</p>
                        <h3 class="mb-0 text-success"><?= count(array_filter($operations, fn($o) => $o['status_yor'] == 'AMAN')) ?></h3>
                    </div>
                    <div class="stat-icon bg-success-subtle">
                        <i class="bi bi-check-circle text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-secondary small mb-1">Status CRITICAL</p>
                        <h3 class="mb-0 text-danger"><?= count(array_filter($operations, fn($o) => $o['status_yor'] == 'CRITICAL')) ?></h3>
                    </div>
                    <div class="stat-icon bg-danger-subtle">
                        <i class="bi bi-exclamation-triangle text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="dashboard-card">
        <div class="mb-3">
            <h6 class="mb-0"><i class="bi bi-calendar-day me-2"></i>Data Tanggal: <?= date('d F Y', strtotime($selectedDate)) ?></h6>
        </div>
        <div class="table-responsive">
            <table class="table table-dark table-striped table-hover" id="reportTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Terminal</th>
                        <th class="text-center">YOR Standar</th>
                        <th class="text-center">YOR Eksisting</th>
                        <th class="text-end">Container CY</th>
                        <th class="text-end">Longstay >3</th>
                        <th class="text-end">Longstay >30</th>
                        <th class="text-end">Kapasitas</th>
                        <th class="text-center">Jml Kapal</th>
                        <th class="text-end">Bongkar/Muat</th>
                        <th class="text-end">Gatepass</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($operations)): ?>
                        <?php foreach ($operations as $index => $op): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($op['terminal_name'] ?? '') ?></td>
                            <td class="text-center"><?= $op['yor_standar'] ?>%</td>
                            <td class="text-center">
                                <?= $op['yor_eksisting'] ? number_format($op['yor_eksisting'], 1) . '%' : '-' ?>
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
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="12" class="text-center text-secondary">Tidak ada data untuk tanggal ini</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <?php if (!empty($operations)): ?>
                <tfoot>
                    <tr class="table-primary">
                        <th colspan="4">TOTAL</th>
                        <th class="text-end"><?= number_format(array_sum(array_column($operations, 'container_cy'))) ?></th>
                        <th class="text-end"><?= number_format(array_sum(array_column($operations, 'container_longstay_3'))) ?></th>
                        <th class="text-end"><?= number_format(array_sum(array_column($operations, 'container_longstay_30'))) ?></th>
                        <th class="text-end"><?= number_format(array_sum(array_column($operations, 'kapasitas_harian_cy'))) ?></th>
                        <th class="text-center"><?= array_sum(array_column($operations, 'jumlah_kapal')) ?></th>
                        <th class="text-end"><?= number_format(array_sum(array_column($operations, 'bongkar_muat'))) ?></th>
                        <th class="text-end"><?= number_format(array_sum(array_column($operations, 'gatepass_total'))) ?></th>
                        <th></th>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Data Harian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Upload File Excel (.xlsx)</label>
                    <input type="file" class="form-control" id="importFile" accept=".xlsx, .xls">
                    <div class="d-flex justify-content-between mt-2">
                        <div class="form-text">Gunakan format file yang sama dengan hasil Export.</div>
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
    // Basic DataTable
    $('#reportTable').DataTable({
        ...App.dataTableDefaults,
        pageLength: 25,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip'
    });

    // Prepare data for Export (Safe JSON Encode)
    <?php
    // Prepare PHP array first
    $jsExportData = [];
    foreach ($operations as $index => $op) {
        $jsExportData[] = [
            $index + 1,
            $op['terminal_name'] ?? '',
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
    
    // Inject Safe JSON
    const exportData = <?= json_encode($jsExportData) ?>;

    const exportHeaders = [
        "No", "Terminal", "YOR Standar", "YOR Eksisting", 
        "Container CY", "Longstay > 3", "Longstay > 30", 
        "Kapasitas Harian", "Jumlah Kapal", "Bongkar/Muat", 
        "Gatepass", "Status"
    ];

    // Handle Custom Export Button (Delegated Event)
    $(document).on('click', '#btnExportExcel', function(e) {
        e.preventDefault();
        console.log('Export button clicked (Delegated)');
        
        // Debug
        if (typeof App === 'undefined' || typeof App.exportDataToExcel !== 'function') {
            alert('Aplikasi belum siap. Silakan refresh halaman.');
            return;
        }

        if (!exportData || exportData.length === 0) {
            alert('Tidak ada data untuk diexport');
            return;
        }
        
        try {
            App.exportDataToExcel(exportData, exportHeaders, 'Laporan_Harian_<?= $selectedDate ?>.xlsx', 'Laporan Harian');
        } catch (err) {
            console.error(err);
            alert('Export gagal: ' + err.message);
        }
    });

    // Handle Download Template (XLSX Client-Side)
    $('#btnDownloadTemplate').on('click', function(e) {
        e.preventDefault();
        
        // Format Template (Header + 1 Dummy Row)
        const templateData = [
            {
                "No": 1,
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
            // Check library
            if (typeof XLSX === 'undefined') throw new Error('Library XLSX belum siap. Pastikan script loaded.');
            
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.json_to_sheet(templateData);
            
            // Auto-width columns (simple estimation)
            const wscols = Object.keys(templateData[0]).map(key => ({wch: Math.max(key.length, 10) + 2}));
            ws['!cols'] = wscols;

            XLSX.utils.book_append_sheet(wb, ws, "Template");
            
            XLSX.writeFile(wb, "Template_Import_Harian.xlsx");
            
        } catch (err) {
            console.error(err);
            alert('Gagal download template: ' + err.message);
        }
    });

    // Handle Import Process
    $('#btnProcessImport').on('click', function() {
        const fileInput = document.getElementById('importFile');
        if (!fileInput.files.length) {
            alert('Pilih file terlebih dahulu!');
            return;
        }

        const file = fileInput.files[0];
        const reader = new FileReader();

        // Show loading
        const btn = $(this);
        const originalText = btn.html();
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Processing...');
        $('#importProgressWrapper').removeClass('d-none');

        reader.onload = function(e) {
            try {
                const data = new Uint8Array(e.target.result);
                // Use XLSX from global scope (loaded via CDN)
                if (typeof XLSX === 'undefined') {
                    throw new Error('Library XLSX belum siap.');
                }
                
                const workbook = XLSX.read(data, {type: 'array'});
                
                // Assume first sheet
                const firstSheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[firstSheetName];
                
                // Parse JSON
                const jsonData = XLSX.utils.sheet_to_json(worksheet);
                
                if (jsonData.length === 0) {
                    throw new Error('File kosong atau format salah');
                }

                console.log('Import Data Preview:', jsonData.slice(0, 3));

                // Send to Server
                fetch('<?= base_url('reports/daily/import') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        date: '<?= $selectedDate ?>',
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
                    alert('Terjadi kesalahan saat mengirim data: ' + err.message);
                })
                .finally(() => {
                    btn.prop('disabled', false).html(originalText);
                    $('#importProgressWrapper').addClass('d-none');
                });

            } catch (err) {
                console.error(err);
                alert('Gagal membaca file Excel: ' + err.message);
                btn.prop('disabled', false).html(originalText);
                $('#importProgressWrapper').addClass('d-none');
            }
        };
        
        reader.readAsArrayBuffer(file);
    });
});
</script>
<?= $this->endSection() ?>
```
