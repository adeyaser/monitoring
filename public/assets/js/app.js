/**
 * =====================================================
 * OPERATIONAL INFORMATION SYSTEM - PELINDO
 * Main Application JavaScript
 * =====================================================
 */

document.addEventListener('DOMContentLoaded', function () {
    // Start NProgress on load
    if (typeof NProgress !== 'undefined') {
        NProgress.configure({ showSpinner: false, parent: 'body' });
        NProgress.start();
    }

    // Initialize all components
    initSidebar();
    initClock();
    initFullscreen();
    initTooltips();
    initConfirmDelete();

    // End NProgress when everything is loaded
    window.addEventListener('load', function () {
        if (typeof NProgress !== 'undefined') {
            NProgress.done();
        }
    });

    // Optional: hook into fetch/xhr for dynamic loading
    const originalFetch = window.fetch;
    window.fetch = async function (...args) {
        if (typeof NProgress !== 'undefined') NProgress.start();
        try {
            const response = await originalFetch(...args);
            return response;
        } finally {
            if (typeof NProgress !== 'undefined') NProgress.done();
        }
    };
});

/**
 * Sidebar Toggle - Hide/Show Navigator
 */
function initSidebar() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarClose = document.getElementById('sidebarClose');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    // Restore sidebar state from localStorage
    const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (sidebarCollapsed) {
        document.body.classList.add('sidebar-collapsed');
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            // Toggle sidebar collapsed state
            document.body.classList.toggle('sidebar-collapsed');

            // Save preference to localStorage
            const isCollapsed = document.body.classList.contains('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);

            // For mobile - also toggle active class
            if (window.innerWidth < 992) {
                sidebar.classList.toggle('active');
                sidebarOverlay.classList.toggle('active');
            }
        });
    }

    if (sidebarClose) {
        sidebarClose.addEventListener('click', function () {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.classList.add('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', 'true');
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function () {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });
    }

    // Set active menu based on current URL
    setActiveMenu();
}

/**
 * Set Active Menu
 */
function setActiveMenu() {
    const currentPath = window.location.pathname;
    const menuLinks = document.querySelectorAll('.nav-link');

    menuLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.includes(href) && href !== '#') {
            link.classList.add('active');

            // Expand parent submenu if exists
            const parentSubmenu = link.closest('.submenu');
            if (parentSubmenu) {
                parentSubmenu.classList.add('show');
                const parentToggle = parentSubmenu.previousElementSibling;
                if (parentToggle) {
                    parentToggle.setAttribute('aria-expanded', 'true');
                }
            }
        }
    });
}

/**
 * Real-time Clock
 */
function initClock() {
    const dateElement = document.getElementById('currentDate');
    const timeElement = document.getElementById('currentTime');

    function updateClock() {
        const now = new Date();

        if (dateElement) {
            dateElement.textContent = now.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
        }

        if (timeElement) {
            timeElement.textContent = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }
    }

    updateClock();
    setInterval(updateClock, 1000);
}

/**
 * Fullscreen Toggle
 */
function initFullscreen() {
    const fullscreenBtn = document.getElementById('fullscreenToggle');

    if (fullscreenBtn) {
        fullscreenBtn.addEventListener('click', function () {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
                fullscreenBtn.innerHTML = '<i class="bi bi-fullscreen-exit"></i>';
            } else {
                document.exitFullscreen();
                fullscreenBtn.innerHTML = '<i class="bi bi-fullscreen"></i>';
            }
        });
    }
}

/**
 * Initialize Bootstrap Tooltips
 */
function initTooltips() {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
}

/**
 * Confirm Delete with SweetAlert2
 */
function initConfirmDelete() {
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const url = this.getAttribute('data-url');
            const name = this.getAttribute('data-name') || 'item ini';

            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus ${name}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff1744',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: '#21262d',
                color: '#f0f6fc'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteItem(url);
                }
            });
        });
    });
}

/**
 * Delete Item via AJAX
 */
function deleteItem(url) {
    fetch(url, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: data.message || 'Data berhasil dihapus',
                    icon: 'success',
                    background: '#21262d',
                    color: '#f0f6fc'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Gagal!',
                    text: data.message || 'Terjadi kesalahan',
                    icon: 'error',
                    background: '#21262d',
                    color: '#f0f6fc'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan pada server',
                icon: 'error',
                background: '#21262d',
                color: '#f0f6fc'
            });
        });
}

/**
 * Format Number with Thousand Separator
 */
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

/**
 * Show Loading Overlay
 */
function showLoading() {
    Swal.fire({
        title: 'Loading...',
        html: '<div class="loading-spinner"></div>',
        showConfirmButton: false,
        allowOutsideClick: false,
        background: '#21262d',
        color: '#f0f6fc'
    });
}

/**
 * Hide Loading Overlay
 */
function hideLoading() {
    Swal.close();
}

/**
 * Show Toast Notification
 */
function showToast(message, type = 'success') {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: '#21262d',
        color: '#f0f6fc',
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    Toast.fire({
        icon: type,
        title: message
    });
}

/**
 * DataTable Default Options
 */
const dataTableDefaults = {
    responsive: true,
    pageLength: 10,
    language: {
        search: "Cari:",
        lengthMenu: "Tampilkan _MENU_ data",
        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
        infoEmpty: "Tidak ada data",
        infoFiltered: "(difilter dari _MAX_ total data)",
        zeroRecords: "Tidak ada data yang cocok",
        paginate: {
            first: "Pertama",
            last: "Terakhir",
            next: "<i class='bi bi-chevron-right'></i>",
            previous: "<i class='bi bi-chevron-left'></i>"
        }
    },
    dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip'
};

/**
 * Animate Counter
 */
function animateCounter(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        const current = Math.floor(progress * (end - start) + start);
        element.textContent = formatNumber(current);
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

/**
 * Create Gauge Chart
 */
function createGaugeChart(canvasId, value, maxValue = 100) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    const centerX = canvas.width / 2;
    const centerY = canvas.height - 10;
    const radius = Math.min(centerX, centerY) - 10;

    // Clear canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Background arc
    ctx.beginPath();
    ctx.arc(centerX, centerY, radius, Math.PI, 2 * Math.PI);
    ctx.lineWidth = 15;
    ctx.strokeStyle = 'rgba(255,255,255,0.1)';
    ctx.stroke();

    // Value arc
    const percentage = value / maxValue;
    const endAngle = Math.PI + (Math.PI * percentage);

    // Gradient based on value
    const gradient = ctx.createLinearGradient(0, 0, canvas.width, 0);
    if (percentage < 0.65) {
        gradient.addColorStop(0, '#00c853');
        gradient.addColorStop(1, '#00e676');
    } else if (percentage < 0.85) {
        gradient.addColorStop(0, '#ff9800');
        gradient.addColorStop(1, '#ffca28');
    } else {
        gradient.addColorStop(0, '#ff1744');
        gradient.addColorStop(1, '#ff5252');
    }

    ctx.beginPath();
    ctx.arc(centerX, centerY, radius, Math.PI, endAngle);
    ctx.lineWidth = 15;
    ctx.lineCap = 'round';
    ctx.strokeStyle = gradient;
    ctx.stroke();

    return true;
}

/**
 * Export Table to CSV
 */
function exportTableToCSV(tableId, filename = 'export.csv') {
    const table = document.getElementById(tableId);
    if (!table) return;

    let csv = [];
    const rows = table.querySelectorAll('tr');

    rows.forEach(row => {
        const cols = row.querySelectorAll('td, th');
        const rowData = [];
        cols.forEach(col => {
            rowData.push('"' + col.innerText.replace(/"/g, '""') + '"');
        });
        csv.push(rowData.join(','));
    });

    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');

    if (navigator.msSaveBlob) {
        navigator.msSaveBlob(blob, filename);
    } else {
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
    }
}

/**
 * Debounce Function
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Fetch API Wrapper
 */
async function fetchAPI(url, options = {}) {
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    };

    try {
        const response = await fetch(url, { ...defaultOptions, ...options });
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('API Error:', error);
        throw error;
    }
}

/**
 * Export Table to Excel using SheetJS
 */
function exportTableToExcel(tableId, filename = 'export.xlsx', sheetName = 'Data') {
    const table = document.getElementById(tableId);
    if (!table) {
        console.error('Table not found:', tableId);
        showToast('Tabel tidak ditemukan', 'error');
        return;
    }

    // Check if XLSX library is loaded
    if (typeof XLSX === 'undefined') {
        console.error('SheetJS library not loaded');
        // Fallback to CSV export
        exportTableToCSV(tableId, filename.replace('.xlsx', '.csv'));
        return;
    }

    try {
        // Create workbook from table
        const wb = XLSX.utils.table_to_book(table, { sheet: sheetName });

        // Generate Excel file and trigger download
        XLSX.writeFile(wb, filename);

        showToast('Export berhasil!', 'success');
    } catch (error) {
        console.error('Export error:', error);
        showToast('Gagal mengexport data', 'error');
    }
}

/**
 * Export Data to Excel (from array)
 */
function exportDataToExcel(data, headers, filename = 'export.xlsx', sheetName = 'Data') {
    // Check if XLSX library is loaded
    if (typeof XLSX === 'undefined') {
        console.error('SheetJS library not loaded');
        showToast('Library Excel tidak tersedia', 'error');
        return;
    }

    try {
        // Create worksheet data with headers
        const wsData = [headers, ...data];

        // Create worksheet
        const ws = XLSX.utils.aoa_to_sheet(wsData);

        // Create workbook
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, sheetName);

        // Generate Excel file and trigger download
        XLSX.writeFile(wb, filename);

        showToast('Export berhasil!', 'success');
    } catch (error) {
        console.error('Export error:', error);
        showToast('Gagal mengexport data', 'error');
    }
}

// Export functions for global use
window.App = {
    showLoading,
    hideLoading,
    showToast,
    formatNumber,
    deleteItem,
    createGaugeChart,
    exportTableToCSV,
    exportTableToExcel,
    exportDataToExcel,
    fetchAPI,
    debounce,
    animateCounter,
    dataTableDefaults
};

