<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= esc($title ?? 'Dashboard Monitoring') ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- NProgress -->
    <link href="https://unpkg.com/nprogress@0.2.0/nprogress.css" rel="stylesheet">
    <script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>

    <!-- Theme Init (Prevent Flash) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>

    <!-- Custom CSS -->
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body>
    <!-- Wrapper -->
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo-wrapper">
                    <div class="logo-icon">
                        <i class="bi bi-tsunami"></i>
                    </div>
                    <div class="logo-text">
                        <span class="brand-name"><?= esc($settings['company_name'] ?? 'PELINDO') ?></span>
                        <span class="brand-subtitle">Monitoring System</span>
                    </div>
                </div>
                <button class="sidebar-toggle d-lg-none" id="sidebarClose">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            
            <div class="sidebar-body">
                <nav class="sidebar-nav">
                    <?= $this->include('layouts/sidebar_menu') ?>
                </nav>
            </div>
            
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <div class="user-details">
                        <span class="user-name"><?= esc($user['full_name'] ?? 'Guest') ?></span>
                        <span class="user-role"><?= esc($user['group_name'] ?? '') ?></span>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Overlay for mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="main-header">
                <div class="header-left">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="page-title">
                        <h1><?= esc($pageTitle ?? 'Dashboard') ?></h1>
                    </div>
                </div>
                
                <div class="header-right">
                    <div class="header-datetime">
                        <span class="header-date" id="currentDate"><?= date('d F Y') ?></span>
                        <span class="header-time" id="currentTime"><?= date('H:i:s') ?></span>
                    </div>
                    
                    <div class="header-actions">
                        <button class="btn-icon" id="themeToggle" title="Toggle Dark/Light Mode">
                            <i class="bi bi-moon-fill" id="themeIcon"></i>
                        </button>
                        <button class="btn-icon" id="refreshData" title="Refresh Data">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                        <button class="btn-icon" id="fullscreenToggle" title="Fullscreen">
                            <i class="bi bi-fullscreen"></i>
                        </button>
                        <div class="dropdown">
                            <button class="btn-icon dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= base_url('profile') ?>"><i class="bi bi-person me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('settings') ?>"><i class="bi bi-gear me-2"></i>Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <div class="main-body">
                <!-- Alert Messages -->
                <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <?= $this->renderSection('content') ?>
            </div>
        </main>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- SheetJS for Excel Export -->
    <script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
    
    <!-- Theme Toggle Script -->
    <script>
        (function() {
            const html = document.documentElement;
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            
            // Get saved theme or default to dark
            const savedTheme = localStorage.getItem('theme') || 'dark';
            // html.setAttribute('data-bs-theme', savedTheme); // Handled in head
            updateIcon(savedTheme);
            
            function updateIcon(theme) {
                if (themeIcon) {
                    themeIcon.className = theme === 'dark' ? 'bi bi-moon-fill' : 'bi bi-sun-fill';
                }
            }
            
            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    const currentTheme = html.getAttribute('data-bs-theme');
                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    
                    html.setAttribute('data-bs-theme', newTheme);
                    localStorage.setItem('theme', newTheme);
                    updateIcon(newTheme);
                    
                    // Update charts if they exist
                    if (typeof Chart !== 'undefined') {
                        Chart.helpers.each(Chart.instances, function(chart) {
                            chart.update();
                        });
                    }
                });
            }
        })();
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>
