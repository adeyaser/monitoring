<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Login') ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Theme Init (Prevent Flash) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>
    
    <style>
        /* Dark Theme Variables */
        :root,
        [data-bs-theme="dark"] {
            --primary: #0066cc;
            --primary-dark: #004d99;
            --secondary: #1a1d24;
            --success: #00c853;
            --warning: #ffab00;
            --danger: #ff1744;
            --dark: #0d1117;
            --dark-100: #161b22;
            --dark-200: #21262d;
            --dark-300: #30363d;
            --text-primary: #f0f6fc;
            --text-secondary: #8b949e;
            --glass-bg: rgba(22, 27, 34, 0.8);
            --glass-border: rgba(48, 54, 61, 0.5);
        }
        
        /* Light Theme Variables */
        [data-bs-theme="light"] {
            --primary: #0066cc;
            --primary-dark: #004d99;
            --secondary: #f8f9fa;
            --success: #00a844;
            --warning: #e69500;
            --danger: #dc3545;
            --dark: #f0f2f5;
            --dark-100: #ffffff;
            --dark-200: #e4e6eb;
            --dark-300: #d1d5db;
            --text-primary: #1c1e21;
            --text-secondary: #65676b;
            --glass-bg: rgba(255, 255, 255, 0.9);
            --glass-border: rgba(0, 0, 0, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--dark) 0%, var(--dark-100) 50%, var(--dark-200) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow: hidden;
            transition: background 0.3s ease;
        }
        
        /* Theme Toggle Button */
        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 100;
            width: 44px;
            height: 44px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            color: var(--text-secondary);
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .theme-toggle:hover {
            color: var(--primary);
            transform: rotate(15deg);
        }
        
        [data-bs-theme="dark"] .theme-toggle i::before {
            content: "\F497"; /* moon icon */
        }
        
        [data-bs-theme="light"] .theme-toggle i::before {
            content: "\F5A5"; /* sun icon */
        }
        
        /* Animated background */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }
        
        .bg-animation::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(ellipse at center, rgba(0, 102, 204, 0.1) 0%, transparent 50%);
            animation: rotate 30s linear infinite;
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
        }
        
        .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 48px;
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.05) inset;
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .login-logo .logo-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: white;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0, 102, 204, 0.3);
            animation: pulse 3s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 10px 30px rgba(0, 102, 204, 0.3); }
            50% { transform: scale(1.05); box-shadow: 0 15px 40px rgba(0, 102, 204, 0.4); }
        }
        
        .login-logo h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }
        
        .login-logo p {
            font-size: 14px;
            color: var(--text-secondary);
        }
        
        .form-floating {
            margin-bottom: 20px;
        }
        
        .form-floating > .form-control {
            background: var(--dark-200);
            border: 1px solid var(--dark-300);
            border-radius: 12px;
            color: var(--text-primary);
            height: 56px;
            padding: 16px;
            padding-left: 48px;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        
        .form-floating > .form-control:focus {
            background: var(--dark-200);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(0, 102, 204, 0.15);
        }
        
        .form-floating > label {
            padding-left: 48px;
            color: var(--text-secondary);
        }
        
        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: var(--primary);
        }
        
        .form-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 18px;
            z-index: 10;
            transition: color 0.3s ease;
        }
        
        .form-floating:focus-within .form-icon {
            color: var(--primary);
        }
        
        .btn-login {
            width: 100%;
            height: 56px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 102, 204, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 16px 20px;
            margin-bottom: 24px;
            font-size: 14px;
        }
        
        .alert-danger {
            background: rgba(255, 23, 68, 0.15);
            color: #ff6b8a;
        }
        
        .alert-success {
            background: rgba(0, 200, 83, 0.15);
            color: #6be09c;
        }
        
        .footer-text {
            text-align: center;
            margin-top: 32px;
            font-size: 13px;
            color: var(--text-secondary);
        }
        
        /* Floating particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
            z-index: 0;
        }
        
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--primary);
            border-radius: 50%;
            opacity: 0.3;
            animation: float 15s infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) translateX(0); opacity: 0; }
            10% { opacity: 0.3; }
            90% { opacity: 0.3; }
            100% { transform: translateY(-100vh) translateX(100px); opacity: 0; }
        }
        
        /* Light Mode Overrides for Login Page */
        [data-bs-theme="light"] .login-card {
            background: var(--glass-bg);
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(0, 0, 0, 0.05) inset;
        }
        
        [data-bs-theme="light"] .login-logo h1 {
            color: var(--text-primary);
        }
        
        [data-bs-theme="light"] .login-logo p {
            color: var(--text-secondary);
        }
        
        [data-bs-theme="light"] .form-floating > .form-control {
            background: var(--dark-200);
            border-color: var(--dark-300);
            color: var(--text-primary);
        }
        
        [data-bs-theme="light"] .form-floating > .form-control:focus {
            background: var(--dark-100);
            border-color: var(--primary);
        }
        
        [data-bs-theme="light"] .form-floating > label {
            color: var(--text-secondary);
        }
        
        [data-bs-theme="light"] .form-icon {
            color: var(--text-secondary);
        }
        
        [data-bs-theme="light"] .footer-text {
            color: var(--text-secondary);
        }
        
        [data-bs-theme="light"] .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        
        [data-bs-theme="light"] .alert-success {
            background: rgba(0, 168, 68, 0.1);
            color: #00a844;
        }
        
        [data-bs-theme="light"] .particle {
            background: var(--primary);
            opacity: 0.15;
        }
    </style>
</head>
<body>
    <!-- Theme Toggle Button -->
    <button class="theme-toggle" id="themeToggle" title="Toggle Dark/Light Mode">
        <i class="bi bi-moon-fill" id="themeIcon"></i>
    </button>
    
    <!-- Background Animation -->
    <div class="bg-animation"></div>
    
    <!-- Particles -->
    <div class="particles">
        <?php for($i = 0; $i < 20; $i++): ?>
        <div class="particle" style="
            left: <?= rand(0, 100) ?>%;
            top: <?= rand(0, 100) ?>%;
            animation-delay: <?= rand(0, 15) ?>s;
            animation-duration: <?= rand(10, 20) ?>s;
        "></div>
        <?php endfor; ?>
    </div>
    
    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <div class="logo-icon">
                    <i class="bi bi-tsunami"></i>
                </div>
                <h1><?= esc($settings['company_name'] ?? 'PELINDO') ?></h1>
                <p>Operational Information System</p>
            </div>
            
            <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <form action="<?= base_url('auth/authenticate') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="form-floating position-relative">
                    <i class="bi bi-person form-icon"></i>
                    <input type="text" class="form-control" id="username" name="username" 
                           placeholder="Username" value="<?= old('username') ?>" required>
                    <label for="username">Username atau Email</label>
                </div>
                
                <div class="form-floating position-relative">
                    <i class="bi bi-lock form-icon"></i>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Password" required>
                    <label for="password">Password</label>
                </div>
                
                <button type="submit" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Masuk</span>
                </button>
            </form>
            
            <p class="footer-text">
                &copy; <?= date('Y') ?> <?= esc($settings['company_name'] ?? 'PELINDO') ?>. All rights reserved.
            </p>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
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
                });
            }
        })();
    </script>
</body>
</html>
