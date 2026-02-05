<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="dashboard-container">
    <!-- Top Row: 2 Gauges + Counter + Vessel List -->
    <div class="row g-4 mb-4">
        <!-- YOR Terminal Gauge -->
        <div class="col-xl-2 col-lg-3 col-md-6">
            <div class="dashboard-card yor-card-custom">
                <div class="yor-header">
                    <div class="yor-title">YOR Terminal</div>
                    <div class="yor-date">28 January 2026</div>
                </div>
                
                <div class="yor-gauge-container">
                    <div class="chart-wrapper">
                        <canvas id="yorGaugeNew"></canvas>
                        <div class="yor-overlay-value">82.47%</div>
                    </div>
                </div>
                
                <div class="yor-split-stats">
                    <div class="yor-stat-left">
                        <span class="sub-label">INTER</span>
                        <span class="sub-value">81.29%</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="yor-stat-right">
                        <span class="sub-label">DOM</span>
                        <span class="sub-value">94.25%</span>
                    </div>
                </div>
                
                <div class="yor-footer-stats">
                    <div class="footer-row">
                        <span class="f-label">Capacity</span>
                        <span class="f-value">18,521 <span class="unit">TEUs</span></span>
                    </div>
                    <div class="footer-row">
                        <span class="f-label">Current Used</span>
                        <span class="f-value">15,274 <span class="unit">TEUs</span></span>
                    </div>
                </div>
                
                <div class="yor-bottom-link">View Month History</div>
            </div>
        </div>
        
        <!-- BOR Terminal Gauge -->
        <div class="col-xl-2 col-lg-3 col-md-6">
            <div class="dashboard-card yor-card-custom"> <!-- Reuse style -->
                <div class="yor-header">
                    <div class="yor-title">BOR Terminal</div>
                    <div class="yor-date">28 January 2026</div>
                </div>
                
                <div class="yor-gauge-container">
                    <div class="chart-wrapper">
                        <canvas id="borGaugeNew"></canvas>
                        <!-- Use Average BOR value -->
                        <div class="yor-overlay-value"><?= number_format($avgBor ?? 65.4, 2) ?>%</div>
                    </div>
                </div>
                
                <div class="yor-split-stats">
                    <div class="yor-stat-left">
                        <span class="sub-label">JICT</span>
                        <span class="sub-value">68.20%</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="yor-stat-right">
                        <span class="sub-label">KOJA</span>
                        <span class="sub-value">55.10%</span>
                    </div>
                </div>
                
                <div class="yor-footer-stats">
                    <div class="footer-row">
                        <span class="f-label">Capacity</span>
                        <span class="f-value">25,000 <span class="unit">TEUs</span></span>
                    </div>
                    <div class="footer-row">
                        <span class="f-label">Current Used</span>
                        <span class="f-value">18,500 <span class="unit">TEUs</span></span>
                    </div>
                </div>
                
                <div class="yor-bottom-link">View Month History</div>
            </div>
        </div>
        
        <!-- Container Throughput Counter -->
        <div class="col-xl-3 col-lg-6 col-md-12">
            <div class="dashboard-card counter-card-custom">
                <!-- Title centered inside -->
                <div class="counter-title-custom">CONTAINER THROUGHPUT 2026</div>
                
                <div class="throughput-counter-wrapper">
                    <div class="counter-display-custom" id="throughputCounter">
                        <?php 
                        $throughputValue = $throughput['throughput_teus'] ?? 771593;
                        $digits = str_pad($throughputValue, 7, '0', STR_PAD_LEFT);
                        foreach (str_split($digits) as $digit): 
                        ?>
                        <div class="counter-digit-tile">
                            <span><?= $digit ?></span>
                            <div class="digit-split-line"></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="counter-footer-custom">
                    <div class="counter-note">Based on activity</div>
                    <div class="counter-unit-custom">TEUs</div>
                </div>
            </div>
        </div>
        
        <!-- Vessel Alongside -->
        <div class="col-xl-5 col-lg-12">
            <div class="dashboard-card">
                <div class="card-header-custom">
                    <span class="card-title">Vessel Alongside</span>
                    <span class="badge bg-primary"><?= count($vessels) ?> vessels currently at berth</span>
                </div>
                <div class="vessel-list-detailed">
                    <?php if (empty($vessels)): ?>
                        <div class="text-center p-4">No vessels currently at berth</div>
                    <?php else: ?>
                        <?php foreach ($vessels as $idx => $vessel): 
                            // Calculate berthing time string (simulation or real)
                            $bt = new DateTime($vessel['berthing_time']);
                            $now = new DateTime();
                            $diff = $bt->diff($now);
                            $bt_str = "{$diff->d} day {$diff->h} hour {$diff->i} min";
                            
                            // Mock badges for design if not in DB (simulation)
                            $badges = ["00" . ($idx + 4), "00" . ($idx + 5)];
                        ?>
                        <div class="vessel-item-detailed">
                            <div class="vessel-top-row">
                                <span class="berth-badge">Berth No: <?= $idx + 1 ?></span>
                            </div>
                            <div class="vessel-content-grid">
                                <div class="vessel-identity">
                                    <div class="vessel-name-lg"><?= esc($vessel['vessel_name']) ?></div>
                                    <div class="berthing-time">Berthing Time: <?= $bt_str ?></div>
                                </div>
                                <div class="vessel-dates">
                                    <div class="date-row"><span class="label-dim">ATB:</span> <span class="val-highlight"><?= date('d M Y H:i', strtotime($vessel['berthing_time'])) ?></span></div>
                                    <div class="date-row"><span class="label-dim">ETD:</span> <span class="val-highlight"><?= date('d M Y H:i', strtotime($vessel['etd'])) ?></span></div>
                                </div>
                                <div class="vessel-stats-group">
                                    <div class="stat-row">
                                        <span class="label-dim-wide">BCH ET:</span> <span class="val-highlight"><?= (int)$vessel['bch_et'] ?></span>
                                        <span class="label-dim-wide ms-2">BCH AWT:</span> <span class="val-highlight"><?= (int)$vessel['bch_awt'] ?></span>
                                    </div>
                                    <div class="stat-row">
                                        <span class="label-dim-wide">BSH ET:</span> <span class="val-highlight"><?= (int)$vessel['bsh_et'] ?></span>
                                        <span class="label-dim-wide ms-2">BSH AWT:</span> <span class="val-highlight"><?= (int)$vessel['bsh_awt'] ?></span>
                                    </div>
                                </div>
                                <div class="vessel-badges">
                                    <?php foreach ($badges as $badge): ?>
                                        <span class="code-badge"><?= $badge ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="vessel-progress-section">
                                <?php 
                                    $discharged_pct = ($vessel['moves_total'] > 0) ? round(($vessel['moves_completed'] / $vessel['moves_total']) * 100) : 0;
                                    $loading_pct = ($vessel['moves_total'] > 0) ? round((($vessel['moves_total'] - $vessel['remaining_moves']) / $vessel['moves_total']) * 100) : 0; // Mock logic for loading
                                    if($loading_pct > 100) $loading_pct = 90; // Limit for visual
                                ?>
                                <div class="prog-text-left">
                                    <span class="text-dim"><?= $vessel['remaining_moves'] ?> left</span>
                                    <span class="text-success-bold">D <?= $discharged_pct ?>%</span>
                                </div>
                                <div class="bars-container">
                                    <div class="bar-wrapper">
                                        <div class="bar-fill bg-success" style="width: <?= $discharged_pct ?>%"></div>
                                    </div>
                                    <div class="bar-wrapper">
                                        <div class="bar-fill <?= ($loading_pct > 80 ? 'bg-success' : 'bg-danger') ?>" style="width: <?= $loading_pct ?>%"></div>
                                    </div>
                                </div>
                                <div class="prog-text-right">
                                    <span class="<?= ($loading_pct > 80 ? 'text-success-bold' : 'text-danger-bold') ?>"><?= $loading_pct ?>% L</span>
                                    <span class="text-dim"><?= $vessel['remaining_moves'] ?> left</span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Second Row: Charts -->
    <div class="row g-4 mb-4">
        <!-- Traffic / TRT Daily Chart (Left) -->
        <div class="col-xl-6 col-lg-12">
            <div class="dashboard-card trt-card-custom">
                <!-- Custom Tab Header -->
                <div class="trt-tabs-header">
                    <div class="trt-tab">Longest Truck</div>
                    <div class="trt-tab">Traffic Receiving & Delivery per Hour</div>
                    <div class="trt-tab">TRT Today</div>
                    <div class="trt-tab active">TRT Daily</div>
                </div>
                
                <div class="chart-container" style="height: 350px; padding: 10px;">
                    <canvas id="trtDailyChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Throughput Chart (Center) -->
        <div class="col-xl-3 col-lg-6">
            <div class="dashboard-card trt-card-custom"> <!-- Reusing styling -->
                <!-- Tabs -->
                <div class="trt-tabs-header" style="justify-content: space-between; gap: 5px;">
                    <div class="trt-tab" style="font-size: 11px;">Ship Call</div>
                    <div class="trt-tab active" style="font-size: 11px;">Throughput (BOX)</div>
                    <div class="trt-tab" style="font-size: 11px;">Throughput (TEUs)</div>
                </div>
                
                <div class="throughput-subtitle" style="font-size: 10px; color: #6c757d; margin-bottom: 10px;">
                    Total until January 2026: <span style="font-weight:700">44,260 BOX</span>
                </div>
                
                <div class="chart-container" style="height: 220px;">
                    <canvas id="throughputDailyChart"></canvas>
                </div>
                
                <div class="throughput-footer" style="font-size: 9px; color: #adb5bd; margin-top: 10px; font-style: italic;">
                    Based on vessel's departure
                </div>
            </div>
        </div>
        
        <!-- Box Ship Hour (Right) -->
        <div class="col-xl-3 col-lg-6">
            <div class="dashboard-card trt-card-custom"> <!-- Reuse styling -->
                <div class="yor-header" style="margin-bottom: 15px;">
                    <div class="yor-title">Box Ship Hour (BSH)</div>
                    <div class="yor-date" style="font-weight: 400; color: #6c757d;">January 2026</div>
                </div>
                
                <div class="chart-container" style="height: 220px;">
                    <canvas id="bshChart"></canvas>
                </div>
                
                <div class="yor-bottom-link" style="margin-top: 15px; text-align: center;">View History</div>
            </div>
        </div>
    </div>
    
    <!-- Bottom Row: Terminal Slot & Booking Distribution -->
    <div class="row g-4 mb-4">
        <!-- Terminal Slot Chart -->
        <div class="col-xl-6 col-lg-12">
            <div class="dashboard-card">
                <div class="card-header-custom">
                    <span class="card-title">Internal Slot TBS (06:00-09:00)</span>
                </div>
                <div class="chart-container" style="height: 200px;">
                    <canvas id="terminalSlotChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Booking Distribution -->
        <div class="col-xl-6 col-lg-12">
            <div class="dashboard-card trt-card-custom">
                <!-- Tabs -->
                <div class="trt-tabs-header">
                    <div class="trt-tab">Quota</div>
                    <div class="trt-tab">Booking</div>
                    <div class="trt-tab active">Realization</div>
                    <div class="trt-tab">Truck Arrival</div>
                    <div class="trt-tab">Waiting Area</div>
                </div>
                
                <div class="chart-title" style="font-weight: 700; margin-bottom: 10px;">Booking Distribution</div>
                
                <div class="chart-container" style="height: 300px;">
                    <canvas id="bookingDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Server Data Injection
    const dbData = {
        yor: <?= json_encode($yor ?? []) ?>,
        bor: <?= json_encode($bor ?? []) ?>,
        throughput: <?= json_encode($throughput ?? []) ?>,
        bsh: <?= json_encode($bsh ?? []) ?>,
        booking: <?= json_encode($bookingDist ?? []) ?>
    };

    console.log('Dashboard Data Loaded:', dbData);

document.addEventListener('DOMContentLoaded', function() {
    // Chart.js Theme Defaults
    const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
    const themeColor = isDark ? '#e6edf3' : '#212529';
    const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)';
    
    Chart.defaults.color = themeColor;
    Chart.defaults.borderColor = gridColor;
    
    // Defensive check for Chart.js 4.x defaults structure
    if (Chart.defaults.plugins && Chart.defaults.plugins.legend && Chart.defaults.plugins.legend.labels) {
        Chart.defaults.plugins.legend.labels.color = themeColor;
    }
    
    if (Chart.defaults.scales) {
        if (Chart.defaults.scales.linear) {
            if (!Chart.defaults.scales.linear.grid) Chart.defaults.scales.linear.grid = {};
            if (!Chart.defaults.scales.linear.ticks) Chart.defaults.scales.linear.ticks = {};
            Chart.defaults.scales.linear.grid.color = gridColor;
            Chart.defaults.scales.linear.ticks.color = themeColor;
        }
    }

    // Initialize Gauges
    initGauges();
    
    // Initialize Charts
    initTrendChart();
    initThroughputChart();
    initBSHChart();
    initTerminalSlotChart();
    initBookingChart();
    
    // TRT Tabs Interaction & Chart Switching (Scoped to Traffic Card)
    const trafficCard = document.getElementById('trtDailyChart').closest('.trt-card-custom'); // Target the specific card container
    if (trafficCard) {
        const trtTabs = trafficCard.querySelectorAll('.trt-tab');
        trtTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active from ONLY these tabs
                trtTabs.forEach(t => t.classList.remove('active'));
                // Add active to clicked
                this.classList.add('active');
                
                // Trigger Chart Update
                updateTrafficChart(this.innerText.trim());
            });
        });
    }
});

// Gauge Charts
function initGauges() {
    // YOR Gauge (Red)
    const yorVal = parseFloat(dbData.yor.used_percentage) || 82.47;
    const ctxYor = document.getElementById('yorGaugeNew').getContext('2d');
    new Chart(ctxYor, {
        type: 'doughnut',
        data: {
            labels: ['Used', 'Free'],
            datasets: [{
                data: [yorVal, 100 - yorVal],
                backgroundColor: ['#b91c1c', '#e5e7eb'],
                borderWidth: 0,
                borderRadius: 20,
                cutout: '85%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            rotation: -90,
            circumference: 180,
            plugins: { legend: { display: false }, tooltip: { enabled: false } }
        }
    });

    // BOR Gauge (Red to match YOR style)
    const borVal = parseFloat(dbData.bor.bor_percentage) || 65.4;
    const ctxBor = document.getElementById('borGaugeNew').getContext('2d');
    new Chart(ctxBor, {
        type: 'doughnut',
        data: {
            labels: ['Used', 'Free'],
            datasets: [{
                data: [borVal, 100 - borVal],
                backgroundColor: ['#b91c1c', '#e5e7eb'],
                borderWidth: 0,
                borderRadius: 20,
                cutout: '85%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            rotation: -90,
            circumference: 180,
            plugins: { legend: { display: false }, tooltip: { enabled: false } }
        }
    });
}

// Traffic / TRT Chart Manager
let trafficChartInstance = null;

function initTrendChart() {
    // Initial Load as TRT Daily
    updateTrafficChart('TRT Daily');
}

function updateTrafficChart(type) {
    const ctx = document.getElementById('trtDailyChart').getContext('2d');
    const randomData = (min, max, count) => Array.from({length: count}, () => Math.floor(Math.random() * (max - min + 1) + min));
    
    let labels, datasets, yTitle;

    // Destroy existing if exists
    if (trafficChartInstance) {
        trafficChartInstance.destroy();
    }

    if (type === 'TRT Daily') {
        labels = Array.from({length: 28}, (_, i) => i + 1);
        yTitle = 'TRT (Min)';
        datasets = [
            { label: 'REC INTER', data: randomData(40, 100, 28), borderColor: '#0d47a1', backgroundColor: '#0d47a1', borderWidth: 2, borderDash: [5, 5], tension: 0.1, pointRadius: 3 },
            { label: 'REC DOM', data: randomData(20, 60, 28), borderColor: '#fd7e14', backgroundColor: '#fd7e14', borderWidth: 2, borderDash: [5, 5], tension: 0.1, pointRadius: 3 },
            { label: 'DEL INTER', data: randomData(30, 80, 28), borderColor: '#198754', backgroundColor: '#198754', borderWidth: 2, borderDash: [5, 5], tension: 0.1, pointRadius: 3 },
            { label: 'DEL DOM', data: randomData(50, 140, 28), borderColor: '#000000', backgroundColor: '#000000', borderWidth: 2, tension: 0.1, pointRadius: 3 } // Solid
        ];
    } else if (type === 'TRT Today') {
        labels = Array.from({length: 24}, (_, i) => `${i}:00`);
        yTitle = 'TRT (Min)';
        datasets = [
            { label: 'Average TRT', data: randomData(30, 90, 24), borderColor: '#0d6efd', backgroundColor: 'rgba(13, 110, 253, 0.1)', borderWidth: 2, fill: true, tension: 0.4 }
        ];
    } else if (type === 'Traffic Receiving & Delivery per Hour') {
        labels = Array.from({length: 24}, (_, i) => `${i}:00`);
        yTitle = 'Volume (Units)';
        datasets = [
            { label: 'Receiving', data: randomData(50, 200, 24), borderColor: '#198754', backgroundColor: 'rgba(25, 135, 84, 0.1)', borderWidth: 2, fill: true, tension: 0.4 },
            { label: 'Delivery', data: randomData(40, 180, 24), borderColor: '#dc3545', backgroundColor: 'rgba(220, 53, 69, 0.1)', borderWidth: 2, fill: true, tension: 0.4 }
        ];
    } else { // Longest Truck
        labels = ['Gate 1', 'Gate 2', 'Gate 3', 'Gate 4', 'Gate 5'];
        yTitle = 'Duration (Min)';
        datasets = [
            { label: 'Waiting Time', data: randomData(10, 120, 5), backgroundColor: ['#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#dc3545'], borderWidth: 1,  type: 'bar' }
        ];
    }

    trafficChartInstance = new Chart(ctx, {
        type: datasets[0].type || 'line',
        data: { labels: labels, datasets: datasets },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8, padding: 20 } },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                x: { grid: { } },
                y: { title: { display: true, text: yTitle }, beginAtZero: true, grid: { } }
            }
        }
    });
}

// Berth History Chart
function initBerthHistoryChart() {
    const ctx = document.getElementById('berthHistoryChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00', '24:00'],
            datasets: [{
                label: 'Vessels',
                data: [3, 4, 5, 4, 3, 4, 3],
                borderColor: '#0066cc',
                backgroundColor: 'rgba(0, 102, 204, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: { },
                    ticks: {
                        font: {
                            size: 10
                        }
                    }
                },
                y: {
                    grid: { },
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}

// Terminal Slot Chart (Internal Slot TBS)
function initTerminalSlotChart() {
    const ctx = document.getElementById('terminalSlotChart').getContext('2d');
    
    // Slots Labels
    const slots = ['1C', '1D', '1E', '1F', '1G', '1Z', 'D1', '2A', '2B', '2C', '2Z', '3A', '3B', '3C', '3D', '3Z', '4A', '4B', '5A', '5B', '5C', '5D', '5E', '5F', '5G', '5Z', '6A', '6B', '6C', '6D', '6Z', '7A', '7B', 'RCV'];
    
    // Generate dual labels: [SlotName, Percentage]
    const labels = slots.map(s => {
        const pct = Math.floor(Math.random() * 110) + '%';
        return [s, pct]; // Multiline label
    });

    const count = slots.length;
    const randomArray = (max) => Array.from({length: count}, () => Math.random() < 0.3 ? Math.floor(Math.random() * max) : 0); // Sparse data

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Booking (delivery)',
                    data: randomArray(10), // Mostly low
                    backgroundColor: '#2e7d32', // Green
                },
                {
                    label: 'Booking (receiving)',
                    data: randomArray(10),
                    backgroundColor: '#1565c0', // Blue
                },
                {
                    label: 'Post Gate',
                    data: randomArray(5),
                    backgroundColor: '#c62828', // Red
                },
                {
                    label: 'Waiting Area',
                    data: randomArray(8),
                    backgroundColor: '#ef6c00', // Orange
                },
                {
                    label: 'Block',
                    data: randomArray(15), 
                    backgroundColor: '#0277bd', // Light Blue
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: true,
                    grid: { display: false },
                    ticks: {
                        font: { size: 10 },
                        callback: function(val, index) {
                            // Show multiline
                            const label = this.getLabelForValue(val);
                            if (Array.isArray(label)) return label;
                            return label;
                        }
                    }
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    grid: { color: 'rgba(255,255,255,0.15)' }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 8,
                        font: { size: 11 }
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            }
        }
    });
}

// Throughput Chart Manager
let throughputChartInstance = null;

function initThroughputChart() {
    // Tab Interaction
    const tpTabs = document.getElementById('throughputDailyChart').closest('.trt-card-custom').querySelectorAll('.trt-tab'); // Scope to this card
    tpTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tpTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            updateThroughputChart(this.innerText.trim());
        });
    });

    // Initial Load
    updateThroughputChart('Throughput (BOX)');
}

function updateThroughputChart(type) {
    const ctx = document.getElementById('throughputDailyChart').getContext('2d');
    const subtitle = document.querySelector('.throughput-subtitle span');
    
    let datasetData, unitLabel;

    if (throughputChartInstance) {
        throughputChartInstance.destroy();
    }

    // Dummy Data Logic
    if (type.includes('TEUs')) {
        datasetData = [[56000], [12000], [68000]]; // Scaled up for TEUs
        unitLabel = 'TEUs';
        if(subtitle) subtitle.innerText = '68,000 TEUs';
    } else if (type.includes('Ship Call')) {
        datasetData = [[85], [120], [205]];
        unitLabel = 'Calls';
        if(subtitle) subtitle.innerText = '205 Calls';
    } else { // BOX
        datasetData = [[37732], [6528], [44260]]; // Matches Image
        unitLabel = 'BOX';
        if(subtitle) subtitle.innerText = '44,260 BOX';
    }

    throughputChartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan'],
            datasets: [
                {
                    label: 'INTER',
                    data: datasetData[0],
                    backgroundColor: '#198754', // Green
                    borderRadius: 4,
                    barPercentage: 0.6,
                    categoryPercentage: 0.8
                },
                {
                    label: 'DOM',
                    data: datasetData[1],
                    backgroundColor: '#fd7e14', // Orange
                    borderRadius: 4,
                    barPercentage: 0.6,
                    categoryPercentage: 0.8
                },
                {
                    label: 'TOTAL',
                    data: datasetData[2],
                    backgroundColor: '#0d6efd', // Blue
                    borderRadius: 4,
                    barPercentage: 0.6,
                    categoryPercentage: 0.8
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 8,
                        padding: 15,
                        font: { size: 10 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.raw.toLocaleString() + ' ' + unitLabel;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { }
                },
                y: {
                    display: false, // Clean look like image
                    beginAtZero: true,
                    grid: { display: false }
                }
            },
            animation: {
                onComplete: function() {
                    // Draw numbers on top if needed - kept simple for now
                }
            }
        }
    });
}

// BOR Chart (Berth Staging Plan)
function initBORChart() {
    const ctx = document.getElementById('borChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [
                {
                    label: 'Planned',
                    data: [75, 80, 85, 78, 82, 88, 76],
                    borderColor: '#00c853',
                    backgroundColor: 'rgba(0, 200, 83, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Actual',
                    data: [72, 78, 82, 75, 80, 85, 73],
                    borderColor: '#ff9800',
                    backgroundColor: 'rgba(255, 152, 0, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
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
                        padding: 15,
                        font: {
                            size: 11
                        }
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

// BSH Chart (Box Ship Hour)
function initBSHChart() {
    const canvas = document.getElementById('bshChart');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    
    // Data from DB
    const interActual = parseFloat(dbData.bsh.inter_bsh_actual) || 0;
    const domActual = parseFloat(dbData.bsh.dom_bsh_actual) || 0;
    const interTarget = parseFloat(dbData.bsh.inter_bsh_target) || 42;
    const domTarget = parseFloat(dbData.bsh.dom_bsh_target) || 19;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['INTER', 'DOM'],
            datasets: [
                {
                    label: 'Box Ship Hour',
                    data: [interActual, domActual],
                    backgroundColor: [
                        '#198754',  // Green
                        '#fd7e14'   // Orange
                    ],
                    borderRadius: 4,
                    barThickness: 45
                }
            ]
        },
        options: {
            indexAxis: 'y', // Horizontal Bar
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { 
                    enabled: true,
                    callbacks: {
                        label: function(context) {
                            return context.raw + ' hours';
                        }
                    } 
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { display: false },
                    border: { display: false }
                },
                y: {
                    grid: { display: false },
                    ticks: {
                        color: '#6c757d',
                        font: { size: 12, weight: 'bold' },
                        mirror: true,
                        padding: 10
                    },
                    border: { display: false }
                }
            },
            animation: {
                onComplete: function() {
                    const chart = this;
                    const ctx = chart.ctx;
                    
                    chart.data.datasets.forEach((dataset, i) => {
                        const meta = chart.getDatasetMeta(i);
                        meta.data.forEach((bar, index) => {
                            const data = dataset.data[index];
                            ctx.fillStyle = '#212529'; 
                            ctx.font = 'bold 12px sans-serif';
                            ctx.textAlign = 'left';
                            ctx.textBaseline = 'middle';
                            ctx.fillText(data, bar.x + 10, bar.y);
                            
                            // Target Line
                            const target = index === 0 ? interTarget : domTarget;
                            const scale = chart.scales.x;
                            const targetPixel = scale.getPixelForValue(target);
                            
                            ctx.beginPath();
                            ctx.setLineDash([3, 3]);
                            ctx.strokeStyle = '#6c757d';
                            ctx.lineWidth = 1;
                            ctx.moveTo(targetPixel, bar.y - 15);
                            ctx.lineTo(targetPixel, bar.y + 15);
                            ctx.stroke();
                            ctx.setLineDash([]);
                            
                            // Target Label
                            ctx.fillStyle = '#6c757d';
                            ctx.font = '10px sans-serif';
                            ctx.textAlign = 'center';
                            ctx.fillText(target, targetPixel, bar.y - 20);
                        });
                    });
                }
            }
        }
    });
}


// Booking Distribution Chart
let bookingChartInstance = null;

function initBookingChart() {
    // Tabs Interaction
    const bookingTabs = document.getElementById('bookingDistributionChart').closest('.trt-card-custom').querySelectorAll('.trt-tab');
    bookingTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            bookingTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            updateBookingChart(this.innerText.trim());
        });
    });

    updateBookingChart('Realization');
}

function updateBookingChart(type) {
    const ctx = document.getElementById('bookingDistributionChart').getContext('2d');
    
    // Destroy existing
    if (bookingChartInstance) {
        bookingChartInstance.destroy();
    }

    // Generate random data
    const randomData = (max) => Array.from({length: 8}, () => Math.floor(Math.random() * max));
    
    // Dataset configs matches image colors
    // Export Full: Purple #8e24aa
    // Import Full: Green #2e7d32
    // Export Empty: Cyan #00acc1
    // Import Empty: Orange #ef6c00

    let datasets = [
        { label: 'Export Full', data: randomData(150), backgroundColor: '#8e24aa', borderRadius: 2, barPercentage: 0.6 , categoryPercentage: 0.7},
        { label: 'Import Full', data: randomData(150), backgroundColor: '#2e7d32', borderRadius: 2, barPercentage: 0.6 , categoryPercentage: 0.7},
        { label: 'Export Empty', data: randomData(50), backgroundColor: '#00acc1', borderRadius: 2, barPercentage: 0.6 , categoryPercentage: 0.7},
        { label: 'Import Empty', data: randomData(50), backgroundColor: '#ef6c00', borderRadius: 2, barPercentage: 0.6 , categoryPercentage: 0.7},
    ];

    bookingChartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['00-03', '03-06', '06-09', '09-12', '12-15', '15-18', '18-21', '21-24'],
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 8,
                        padding: 15,
                        font: { size: 10 }
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    title: { display: true, text: 'Truck' },
                    beginAtZero: true,
                    suggestedMax: 700,
                    ticks: { stepSize: 125 }
                }
            },
            animation: {
                onComplete: function() {
                    const chart = this;
                    const ctx = chart.ctx;
                    const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';
                    ctx.fillStyle = isDark ? '#e6edf3' : '#212529';
                    ctx.font = 'bold 9px sans-serif';

                    chart.data.datasets.forEach((dataset, i) => {
                        const meta = chart.getDatasetMeta(i);
                        meta.data.forEach((bar, index) => {
                            const data = dataset.data[index];
                            if (data > 0) {
                                ctx.fillText(data, bar.x, bar.y - 2);
                            }
                        });
                    });
                }
            }
        }
    });
}

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
