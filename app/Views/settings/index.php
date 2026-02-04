<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="mb-4">
        <h4 class="mb-1">Pengaturan Sistem</h4>
        <p class="text-secondary mb-0">Konfigurasi aplikasi</p>
    </div>
    
    <form action="<?= base_url('settings/update') ?>" method="POST">
        <?= csrf_field() ?>
        
        <div class="row g-4">
            <!-- General Settings -->
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <h6 class="mb-4"><i class="bi bi-gear me-2"></i>Pengaturan Umum</h6>
                    
                    <div class="mb-3">
                        <label for="company_name" class="form-label">Nama Perusahaan</label>
                        <input type="text" class="form-control" id="company_name" name="settings[company_name]" 
                               value="<?= esc($settings['company_name'] ?? 'PELINDO') ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="company_code" class="form-label">Kode Perusahaan</label>
                        <input type="text" class="form-control" id="company_code" name="settings[company_code]" 
                               value="<?= esc($settings['company_code'] ?? 'PLI') ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="timezone" class="form-label">Zona Waktu</label>
                        <select class="form-select" id="timezone" name="settings[timezone]">
                            <option value="Asia/Jakarta" <?= ($settings['timezone'] ?? 'Asia/Jakarta') == 'Asia/Jakarta' ? 'selected' : '' ?>>WIB - Asia/Jakarta</option>
                            <option value="Asia/Makassar" <?= ($settings['timezone'] ?? '') == 'Asia/Makassar' ? 'selected' : '' ?>>WITA - Asia/Makassar</option>
                            <option value="Asia/Jayapura" <?= ($settings['timezone'] ?? '') == 'Asia/Jayapura' ? 'selected' : '' ?>>WIT - Asia/Jayapura</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Threshold Settings -->
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <h6 class="mb-4"><i class="bi bi-speedometer2 me-2"></i>Threshold Monitoring</h6>
                    
                    <div class="mb-3">
                        <label for="yor_warning" class="form-label">YOR Warning (%)</label>
                        <input type="number" class="form-control" id="yor_warning" name="settings[yor_warning]" 
                               value="<?= esc($settings['yor_warning'] ?? 70) ?>" step="0.01" min="0" max="100">
                        <small class="text-secondary">Status berubah ke WARNING jika YOR mencapai nilai ini</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="yor_critical" class="form-label">YOR Critical (%)</label>
                        <input type="number" class="form-control" id="yor_critical" name="settings[yor_critical]" 
                               value="<?= esc($settings['yor_critical'] ?? 85) ?>" step="0.01" min="0" max="100">
                        <small class="text-secondary">Status berubah ke CRITICAL jika YOR mencapai nilai ini</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="bor_warning" class="form-label">BOR Warning (%)</label>
                        <input type="number" class="form-control" id="bor_warning" name="settings[bor_warning]" 
                               value="<?= esc($settings['bor_warning'] ?? 80) ?>" step="0.01" min="0" max="100">
                    </div>
                    
                    <div class="mb-3">
                        <label for="bor_critical" class="form-label">BOR Critical (%)</label>
                        <input type="number" class="form-control" id="bor_critical" name="settings[bor_critical]" 
                               value="<?= esc($settings['bor_critical'] ?? 95) ?>" step="0.01" min="0" max="100">
                    </div>
                </div>
            </div>
            
            <!-- Display Settings -->
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <h6 class="mb-4"><i class="bi bi-display me-2"></i>Pengaturan Tampilan</h6>
                    
                    <div class="mb-3">
                        <label for="refresh_interval" class="form-label">Interval Refresh (detik)</label>
                        <input type="number" class="form-control" id="refresh_interval" name="settings[refresh_interval]" 
                               value="<?= esc($settings['refresh_interval'] ?? 30) ?>" min="10" max="300">
                        <small class="text-secondary">Interval auto-refresh data dashboard (10-300 detik)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="date_format" class="form-label">Format Tanggal</label>
                        <select class="form-select" id="date_format" name="settings[date_format]">
                            <option value="d/m/Y" <?= ($settings['date_format'] ?? 'd/m/Y') == 'd/m/Y' ? 'selected' : '' ?>>DD/MM/YYYY</option>
                            <option value="Y-m-d" <?= ($settings['date_format'] ?? '') == 'Y-m-d' ? 'selected' : '' ?>>YYYY-MM-DD</option>
                            <option value="d-m-Y" <?= ($settings['date_format'] ?? '') == 'd-m-Y' ? 'selected' : '' ?>>DD-MM-YYYY</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="chart_days" class="form-label">Rentang Data Chart (hari)</label>
                        <input type="number" class="form-control" id="chart_days" name="settings[chart_days]" 
                               value="<?= esc($settings['chart_days'] ?? 14) ?>" min="7" max="90">
                    </div>
                </div>
            </div>
            
            <!-- Notification Settings -->
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <h6 class="mb-4"><i class="bi bi-bell me-2"></i>Pengaturan Notifikasi</h6>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="email_notification" 
                                   name="settings[email_notification]" value="1"
                                   <?= ($settings['email_notification'] ?? 0) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="email_notification">
                                Aktifkan Notifikasi Email
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notification_email" class="form-label">Email Notifikasi</label>
                        <input type="email" class="form-control" id="notification_email" name="settings[notification_email]" 
                               value="<?= esc($settings['notification_email'] ?? '') ?>"
                               placeholder="admin@pelindo.co.id">
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="sound_alert" 
                                   name="settings[sound_alert]" value="1"
                                   <?= ($settings['sound_alert'] ?? 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="sound_alert">
                                Aktifkan Alert Suara (Critical)
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-2"></i>Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
