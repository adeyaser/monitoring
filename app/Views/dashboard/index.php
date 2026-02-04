<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="dashboard-container">
    <!-- Top Row: Gauges + Counter + Pie Charts -->
    <div class="row g-4 mb-4">
        <!-- YOR Gauge -->
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card gauge-card">
                <div class="card-header-custom">
                    <span class="card-title">YOR (%) Overall</span>
                    <span class="badge bg-info-subtle text-info">Real-time</span>
                </div>
                <div class="gauge-wrapper">
                    <canvas id="yorGauge" width="200" height="120"></canvas>
                    <div class="gauge-value" id="yorValue"><?= number_format($avgYor, 2) ?>%</div>
                    <div class="gauge-label">Yard Occupancy Ratio</div>
                </div>
            </div>
        </div>
        
        <!-- BOR Gauge -->
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card gauge-card">
                <div class="card-header-custom">
                    <span class="card-title">BOR (%) Overall</span>
                    <span class="badge bg-warning-subtle text-warning">Real-time</span>
                </div>
                <div class="gauge-wrapper">
                    <canvas id="borGauge" width="200" height="120"></canvas>
                    <div class="gauge-value" id="borValue"><?= number_format($avgBor, 2) ?>%</div>
                    <div class="gauge-label">Berth Occupancy Ratio</div>
                </div>
            </div>
        </div>
        
        <!-- Container Throughput Counter -->
        <div class="col-lg-6 col-md-12">
            <div class="dashboard-card counter-card">
                <div class="card-header-custom">
                    <span class="card-title">Container Throughput <?= date('Y') ?></span>
                    <span class="badge bg-success-subtle text-success">YTD</span>
                </div>
                <div class="throughput-counter">
                    <div class="counter-display" id="throughputCounter">
                        <?php 
                        $throughputValue = $throughput['throughput_teus'] ?? 77192;
                        $digits = str_pad($throughputValue, 7, '0', STR_PAD_LEFT);
                        foreach (str_split($digits) as $digit): 
                        ?>
                        <div class="counter-digit">
                            <span><?= $digit ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="counter-unit">TEUs</div>
                </div>
                <div class="throughput-breakdown">
                    <div class="breakdown-item">
                        <span class="breakdown-label">Export</span>
                        <span class="breakdown-value text-success"><?= number_format($throughput['export_box'] ?? 25700) ?></span>
                    </div>
                    <div class="breakdown-item">
                        <span class="breakdown-label">Import</span>
                        <span class="breakdown-value text-info"><?= number_format($throughput['import_box'] ?? 28500) ?></span>
                    </div>
                    <div class="breakdown-item">
                        <span class="breakdown-label">Transship</span>
                        <span class="breakdown-value text-warning"><?= number_format($throughput['transship_box'] ?? 22992) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Second Row: Trend Charts + Status Cards -->
    <div class="row g-4 mb-4">
        <!-- Trend Line Chart -->
        <div class="col-lg-8">
            <div class="dashboard-card">
                <div class="card-header-custom">
                    <span class="card-title">Trend Data (14 Hari Terakhir)</span>
                    <div class="chart-legend" id="trendLegend"></div>
                </div>
                <div class="chart-container">
                    <canvas id="trendChart" height="280"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Vessel & Status Cards -->
        <div class="col-lg-4">
            <div class="row g-4">
                <!-- Vessel Status -->
                <div class="col-12">
                    <div class="dashboard-card">
                        <div class="card-header-custom">
                            <span class="card-title">Vessel Status</span>
                            <i class="bi bi-cursor card-icon"></i>
                        </div>
                        <div class="vessel-status-grid">
                            <div class="vessel-stat">
                                <div class="vessel-icon scheduled">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                                <div class="vessel-info">
                                    <span class="vessel-count"><?= $vesselStats['scheduled'] ?? 2 ?></span>
                                    <span class="vessel-label">Scheduled</span>
                                </div>
                            </div>
                            <div class="vessel-stat">
                                <div class="vessel-icon berthing">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <div class="vessel-info">
                                    <span class="vessel-count"><?= $vesselStats['berthing'] ?? 1 ?></span>
                                    <span class="vessel-label">Berthing</span>
                                </div>
                            </div>
                            <div class="vessel-stat">
                                <div class="vessel-icon loading">
                                    <i class="bi bi-box-arrow-up"></i>
                                </div>
                                <div class="vessel-info">
                                    <span class="vessel-count"><?= $vesselStats['loading'] ?? 1 ?></span>
                                    <span class="vessel-label">Loading</span>
                                </div>
                            </div>
                            <div class="vessel-stat">
                                <div class="vessel-icon unloading">
                                    <i class="bi bi-box-arrow-down"></i>
                                </div>
                                <div class="vessel-info">
                                    <span class="vessel-count"><?= $vesselStats['unloading'] ?? 1 ?></span>
                                    <span class="vessel-label">Unloading</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- YOR Status Distribution -->
                <div class="col-12">
                    <div class="dashboard-card">
                        <div class="card-header-custom">
                            <span class="card-title">Status YOR Terminal</span>
                        </div>
                        <div class="status-distribution">
                            <div class="status-bar">
                                <div class="status-segment aman" style="width: <?= ($yorStats['count_aman'] ?? 14) / 14 * 100 ?>%"></div>
                                <div class="status-segment warning" style="width: <?= ($yorStats['count_warning'] ?? 0) / 14 * 100 ?>%"></div>
                                <div class="status-segment critical" style="width: <?= ($yorStats['count_critical'] ?? 0) / 14 * 100 ?>%"></div>
                            </div>
                            <div class="status-legend">
                                <div class="legend-item">
                                    <span class="legend-dot aman"></span>
                                    <span>Aman (<?= $yorStats['count_aman'] ?? 14 ?>)</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-dot warning"></span>
                                    <span>Warning (<?= $yorStats['count_warning'] ?? 0 ?>)</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-dot critical"></span>
                                    <span>Critical (<?= $yorStats['count_critical'] ?? 0 ?>)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Third Row: Berth Occupancy + Stacking -->
    <div class="row g-4 mb-4">
        <!-- Berth Occupancy -->
        <div class="col-lg-6">
            <div class="dashboard-card">
                <div class="card-header-custom">
                    <span class="card-title">Berth Occupancy</span>
                    <span class="badge bg-primary-subtle text-primary">Today</span>
                </div>
                <div class="berth-occupancy-list" id="berthList">
                    <!-- Will be populated by JS -->
                    <div class="berth-item">
                        <div class="berth-info">
                            <span class="berth-name">JICT - Berth 1</span>
                            <span class="berth-vessels">3 Vessels</span>
                        </div>
                        <div class="berth-progress">
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width: 72.5%"></div>
                            </div>
                            <span class="berth-percentage">72.5%</span>
                        </div>
                    </div>
                    <div class="berth-item">
                        <div class="berth-info">
                            <span class="berth-name">JICT - Berth 2</span>
                            <span class="berth-vessels">4 Vessels</span>
                        </div>
                        <div class="berth-progress">
                            <div class="progress">
                                <div class="progress-bar bg-warning" style="width: 85%"></div>
                            </div>
                            <span class="berth-percentage">85.0%</span>
                        </div>
                    </div>
                    <div class="berth-item">
                        <div class="berth-info">
                            <span class="berth-name">KOJA - Berth 2</span>
                            <span class="berth-vessels">5 Vessels</span>
                        </div>
                        <div class="berth-progress">
                            <div class="progress">
                                <div class="progress-bar bg-danger" style="width: 90%"></div>
                            </div>
                            <span class="berth-percentage">90.0%</span>
                        </div>
                    </div>
                    <div class="berth-item">
                        <div class="berth-info">
                            <span class="berth-name">NPCT1 - Berth 1</span>
                            <span class="berth-vessels">4 Vessels</span>
                        </div>
                        <div class="berth-progress">
                            <div class="progress">
                                <div class="progress-bar bg-warning" style="width: 78%"></div>
                            </div>
                            <span class="berth-percentage">78.0%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Stacking/Workforce Chart -->
        <div class="col-lg-6">
            <div class="dashboard-card">
                <div class="card-header-custom">
                    <span class="card-title">Terminal Productivity</span>
                    <span class="badge bg-success-subtle text-success">Comparison</span>
                </div>
                <div class="chart-container">
                    <canvas id="productivityChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Fourth Row: Terminal Operations Table -->
    <div class="row g-4">
        <div class="col-12">
            <div class="dashboard-card">
                <div class="card-header-custom">
                    <span class="card-title">Data Operasional Terminal - <?= date('d F Y') ?></span>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="bi bi-upload me-1"></i>Import
                        </button>
                        <button class="btn btn-sm btn-outline-primary" id="exportTable">
                            <i class="bi bi-download me-1"></i>Export
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover" id="terminalTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Terminal</th>
                                <th>YOR (%) Standar</th>
                                <th>YOR (%) Eksisting</th>
                                <th>Container CY</th>
                                <th>Longstay > 3 Hari</th>
                                <th>Longstay > 30 Hari</th>
                                <th>Kapasitas Harian</th>
                                <th>Jumlah Kapal</th>
                                <th>Bongkar/Muat</th>
                                <th>Gatepass Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Gauges
    initGauges();
    
    // Initialize Charts
    initTrendChart();
    initProductivityChart();
    
    // Load Terminal Data
    loadTerminalData();
    
    // Auto refresh
    setInterval(function() {
        refreshDashboardData();
    }, 30000); // Every 30 seconds
});

// Gauge Charts
function initGauges() {
    // YOR Gauge
    createGauge('yorGauge', <?= $avgYor ?>, 'yor');
    
    // BOR Gauge
    createGauge('borGauge', <?= $avgBor ?>, 'bor');
}

function createGauge(canvasId, value, type) {
    const canvas = document.getElementById(canvasId);
    const ctx = canvas.getContext('2d');
    
    const centerX = canvas.width / 2;
    const centerY = canvas.height - 10;
    const radius = 80;
    
    // Clear canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    // Background arc
    ctx.beginPath();
    ctx.arc(centerX, centerY, radius, Math.PI, 2 * Math.PI);
    ctx.lineWidth = 15;
    ctx.strokeStyle = 'rgba(255,255,255,0.1)';
    ctx.stroke();
    
    // Value arc
    const startAngle = Math.PI;
    const endAngle = Math.PI + (Math.PI * (value / 100));
    
    const gradient = ctx.createLinearGradient(0, 0, canvas.width, 0);
    if (value < 65) {
        gradient.addColorStop(0, '#00c853');
        gradient.addColorStop(1, '#00e676');
    } else if (value < 85) {
        gradient.addColorStop(0, '#ff9800');
        gradient.addColorStop(1, '#ffb74d');
    } else {
        gradient.addColorStop(0, '#ff1744');
        gradient.addColorStop(1, '#ff5252');
    }
    
    ctx.beginPath();
    ctx.arc(centerX, centerY, radius, startAngle, endAngle);
    ctx.lineWidth = 15;
    ctx.lineCap = 'round';
    ctx.strokeStyle = gradient;
    ctx.stroke();
    
    // Markers
    [0, 25, 50, 75, 100].forEach(mark => {
        const angle = Math.PI + (Math.PI * (mark / 100));
        const x1 = centerX + (radius - 25) * Math.cos(angle);
        const y1 = centerY + (radius - 25) * Math.sin(angle);
        const x2 = centerX + (radius - 35) * Math.cos(angle);
        const y2 = centerY + (radius - 35) * Math.sin(angle);
        
        ctx.beginPath();
        ctx.moveTo(x1, y1);
        ctx.lineTo(x2, y2);
        ctx.strokeStyle = 'rgba(255,255,255,0.3)';
        ctx.lineWidth = 2;
        ctx.stroke();
    });
}

// Trend Chart
let trendChart;
function initTrendChart() {
    const ctx = document.getElementById('trendChart').getContext('2d');
    
    // Generate sample data
    const labels = [];
    const dataYor = [];
    const dataThroughput = [];
    const dataVessel = [];
    
    for (let i = 13; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        labels.push(date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' }));
        dataYor.push(55 + Math.random() * 25);
        dataThroughput.push(70000 + Math.random() * 15000);
        dataVessel.push(20 + Math.random() * 30);
    }
    
    trendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'YOR (%)',
                    data: dataYor,
                    borderColor: '#00c853',
                    backgroundColor: 'rgba(0, 200, 83, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y'
                },
                {
                    label: 'Throughput (TEUs)',
                    data: dataThroughput,
                    borderColor: '#0066cc',
                    backgroundColor: 'rgba(0, 102, 204, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: false,
                    yAxisID: 'y1'
                },
                {
                    label: 'Vessel',
                    data: dataVessel,
                    borderColor: '#ffab00',
                    backgroundColor: 'rgba(255, 171, 0, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: false,
                    yAxisID: 'y'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#8b949e',
                        usePointStyle: true,
                        padding: 20
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        color: 'rgba(255,255,255,0.05)'
                    },
                    ticks: {
                        color: '#8b949e'
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    grid: {
                        color: 'rgba(255,255,255,0.05)'
                    },
                    ticks: {
                        color: '#8b949e'
                    },
                    title: {
                        display: true,
                        text: 'YOR (%) / Vessel',
                        color: '#8b949e'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false
                    },
                    ticks: {
                        color: '#8b949e'
                    },
                    title: {
                        display: true,
                        text: 'Throughput (TEUs)',
                        color: '#8b949e'
                    }
                }
            }
        }
    });
}

// Productivity Chart
function initProductivityChart() {
    const ctx = document.getElementById('productivityChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['JICT', 'KOJA', 'NPCT1', 'MAL', 'NTT', 'PTP'],
            datasets: [
                {
                    label: 'Bongkar',
                    data: [8500, 5200, 7800, 3200, 2100, 1800],
                    backgroundColor: 'rgba(0, 102, 204, 0.8)',
                    borderRadius: 4
                },
                {
                    label: 'Muat',
                    data: [7200, 4800, 6500, 2800, 1900, 1500],
                    backgroundColor: 'rgba(0, 200, 83, 0.8)',
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#8b949e',
                        usePointStyle: true,
                        padding: 20
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#8b949e'
                    }
                },
                y: {
                    grid: {
                        color: 'rgba(255,255,255,0.05)'
                    },
                    ticks: {
                        color: '#8b949e'
                    }
                }
            }
        }
    });
}

// Global data storage for export
let dashboardTerminalData = [];

// Load Terminal Data
function loadTerminalData() {
    // Sample data based on the second image
    dashboardTerminalData = [
        { no: 1, name: 'JICT', yor_std: 65, yor_eks: null, container: 15514, ls3: 0, ls30: 0, capacity: 0, vessel: 0, cargo: 0, gatepass: 4500, status: 'AMAN' },
        { no: 2, name: 'KOJA', yor_std: 65, yor_eks: null, container: 0, ls3: 0, ls30: 0, capacity: 0, vessel: 0, cargo: 0, gatepass: 2000, status: 'AMAN' },
        { no: 3, name: 'IPC TPK International (OJA)', yor_std: 65, yor_eks: null, container: 10524, ls3: 0, ls30: 0, capacity: 0, vessel: 0, cargo: 0, gatepass: 1500, status: 'AMAN' },
        { no: 4, name: 'IPC TPK International (TSJ)', yor_std: 65, yor_eks: null, container: 0, ls3: 0, ls30: 0, capacity: 0, vessel: 0, cargo: 0, gatepass: 0, status: 'AMAN' },
        { no: 5, name: 'IPC TPK Domestik (MSA)', yor_std: 65, yor_eks: null, container: 10217, ls3: 0, ls30: 0, capacity: 0, vessel: 0, cargo: 0, gatepass: 2000, status: 'AMAN' },
        { no: 6, name: 'IPC TPK Domestik (TEMAS)', yor_std: 65, yor_eks: null, container: 0, ls3: 0, ls30: 0, capacity: 0, vessel: 0, cargo: 0, gatepass: 0, status: 'AMAN' },
        { no: 7, name: 'IPC TPK Domestik (009)', yor_std: 65, yor_eks: null, container: 5094, ls3: 0, ls30: 0, capacity: 0, vessel: 0, cargo: 0, gatepass: 800, status: 'AMAN' },
        { no: 8, name: 'IPC TPK Domestik (ADP)', yor_std: 65, yor_eks: null, container: 8348, ls3: 0, ls30: 0, capacity: 0, vessel: 0, cargo: 0, gatepass: 1500, status: 'AMAN' },
        { no: 9, name: 'IPC TPK Domestik (DHU)', yor_std: 65, yor_eks: null, container: 8666, ls3: 0, ls30: 0, capacity: 0, vessel: 0, cargo: 0, gatepass: 2800, status: 'AMAN' },
        { no: 10, name: 'NPCT 1', yor_std: 65, yor_eks: null, container: 26649, ls3: 0, ls30: 0, capacity: 0, vessel: 0, cargo: 0, gatepass: 1200, status: 'AMAN' },
        { no: 11, name: 'MAL', yor_std: 65, yor_eks: null, container: 5094, ls3: 0, ls30: 0, capacity: 0, vessel: 0, cargo: 0, gatepass: 1500, status: 'AMAN' },
        { no: 12, name: 'NTT', yor_std: 65, yor_eks: null, container: 5000, ls3: 0, ls30: 0, capacity: 0, vessel: 0, cargo: 0, gatepass: 350, status: 'AMAN' },
        { no: 13, name: 'PTP', yor_std: 65, yor_eks: null, container: 700, ls3: 0, ls30: 0, capacity: 0, vessel: 0, cargo: 0, gatepass: 750, status: 'AMAN' },
        { no: 14, name: 'PNP', yor_std: 68, yor_eks: null, container: 3650, ls3: 0, ls30: 0, capacity: 0, vessel: 0, cargo: 0, gatepass: 0, status: 'AMAN' }
    ];
    
    const tbody = document.querySelector('#terminalTable tbody');
    tbody.innerHTML = '';
    
    dashboardTerminalData.forEach(item => {
        const statusClass = item.status === 'AMAN' ? 'status-aman' : (item.status === 'WARNING' ? 'status-warning' : 'status-critical');
        const row = `
            <tr>
                <td>${item.no}</td>
                <td>${item.name}</td>
                <td class="text-center">${item.yor_std}%</td>
                <td class="text-center">${item.yor_eks ?? '-'}</td>
                <td class="text-end">${item.container.toLocaleString()}</td>
                <td class="text-end">${item.ls3.toLocaleString()}</td>
                <td class="text-end">${item.ls30.toLocaleString()}</td>
                <td class="text-end">${item.capacity.toLocaleString()}</td>
                <td class="text-center">${item.vessel}</td>
                <td class="text-end">${item.cargo.toLocaleString()}</td>
                <td class="text-end">${item.gatepass.toLocaleString()}</td>
                <td class="text-center"><span class="status-badge ${statusClass}">${item.status}</span></td>
            </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', row);
    });
    
    // Initialize DataTable
    if (!$.fn.DataTable.isDataTable('#terminalTable')) {
        $('#terminalTable').DataTable({
            pageLength: 15,
            ordering: true,
            searching: true,
            info: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });
    }
}

// Export Handler for Dashboard Table
$('#exportTable').on('click', function(e) {
    e.preventDefault();
    
    if (!dashboardTerminalData || dashboardTerminalData.length === 0) {
        alert('Tidak ada data untuk diexport');
        return;
    }

    // Format data for export
    const exportData = dashboardTerminalData.map(item => [
        item.no,
        item.name,
        item.yor_std + '%',
        item.yor_eks ? item.yor_eks + '%' : '-',
        item.container,
        item.ls3,
        item.ls30,
        item.capacity,
        item.vessel,
        item.cargo,
        item.gatepass,
        item.status
    ]);

    const exportHeaders = [
        "No", "Terminal", "YOR Standar", "YOR Eksisting", 
        "Container CY", "Longstay > 3", "Longstay > 30", 
        "Kapasitas Harian", "Jumlah Kapal", "Bongkar/Muat", 
        "Gatepass", "Status"
    ];

    try {
        if (typeof App !== 'undefined' && typeof App.exportDataToExcel === 'function') {
            App.exportDataToExcel(exportData, exportHeaders, 'Data_Operasional_Terminal.xlsx', 'Data Operasional');
        } else {
            console.error('App.exportDataToExcel not found');
            alert('Gagal load module export. Silakan refresh halaman.');
        }
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
        if (typeof XLSX === 'undefined') throw new Error('Library XLSX belum siap.');
        
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.json_to_sheet(templateData);
        
        // Auto-width
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
    
    // Get current selected date if any, or today
    const selectedDate = new Date().toISOString().split('T')[0];

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
            fetch('<?= base_url('reports/daily/import') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    date: selectedDate,
                    data: jsonData
                })
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    $('#importModal').modal('hide');
                    if(typeof App !== 'undefined') App.showToast('Data berhasil diimport!', 'success');
                    else alert('Berhasil import data!');
                    
                    // Refresh data table
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

// Refresh Dashboard Data
function refreshDashboardData() {
    // Update current time
    const now = new Date();
    document.getElementById('currentDate').textContent = now.toLocaleDateString('id-ID', { 
        day: '2-digit', 
        month: 'long', 
        year: 'numeric' 
    });
    document.getElementById('currentTime').textContent = now.toLocaleTimeString('id-ID');
    
    // Show refresh animation
    document.getElementById('refreshData').classList.add('rotating');
    
    // Fetch new data
    fetch('<?= base_url('dashboard/api/stats') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update gauges and values
                console.log('Data refreshed:', data);
            }
            document.getElementById('refreshData').classList.remove('rotating');
        })
        .catch(error => {
            console.error('Error refreshing data:', error);
            document.getElementById('refreshData').classList.remove('rotating');
        });
}

// Refresh button click handler
document.getElementById('refreshData').addEventListener('click', refreshDashboardData);
</script>
<?= $this->endSection() ?>
