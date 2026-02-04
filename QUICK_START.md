# 🚀 Quick Start - Deploy ke Hosting

## Langkah Cepat (5 Menit)

### 1️⃣ Backup Database

```bash
# Double-click file ini:
backup-database.bat
```

### 2️⃣ Inisialisasi Git

```bash
# Double-click file ini:
git-init.bat
```

### 3️⃣ Buat Repository di GitHub

1. Buka <https://github.com/new>
2. Nama repository: `dashboard-monitoring`
3. Jangan centang "Initialize with README"
4. Klik "Create repository"
5. Copy URL repository (contoh: `https://github.com/username/dashboard-monitoring.git`)

### 4️⃣ Push ke GitHub

```bash
# Double-click file ini dan masukkan URL repository:
git-push.bat
```

### 5️⃣ Deploy ke Hosting

#### Opsi A: Via cPanel (Paling Mudah)

1. Login ke cPanel hosting Anda
2. Buka **Git Version Control**
3. Klik **Create**
4. Masukkan:
   - Repository URL: `https://github.com/username/dashboard-monitoring.git`
   - Repository Path: `public_html` (atau subdirektori)
   - Branch: `main`
5. Klik **Create**
6. Setelah selesai, buka **Terminal** di cPanel
7. Jalankan:

   ```bash
   cd ~/public_html
   composer install --no-dev --optimize-autoloader
   cp .env.example .env
   nano .env  # Edit dengan kredensial production
   chmod -R 777 writable/
   ```

#### Opsi B: Via SSH

```bash
# Login ke server
ssh username@yourserver.com

# Clone repository
cd ~/public_html
git clone https://github.com/username/dashboard-monitoring.git .

# Install dependencies
composer install --no-dev --optimize-autoloader

# Setup environment
cp .env.example .env
nano .env  # Edit dengan kredensial production

# Set permissions
chmod -R 777 writable/
chmod 600 .env
```

### 6️⃣ Setup Database di Hosting

1. Buka **phpMyAdmin** di cPanel
2. Klik **New** untuk buat database baru
3. Nama database: `username_dashboard` (sesuaikan)
4. Klik **Import**
5. Upload file backup dari folder `database/`
6. Klik **Go**

### 7️⃣ Konfigurasi .env di Server

Edit file `.env` di server dengan kredensial production:

```env
CI_ENVIRONMENT = production
app.baseURL = 'https://yourdomain.com/'

database.default.hostname = localhost
database.default.database = username_dashboard
database.default.username = username_dbuser
database.default.password = your_password
```

### 8️⃣ Test Website

1. Buka browser: `https://yourdomain.com`
2. Login dengan:
   - Username: `admin`
   - Password: `admin123`
3. **PENTING**: Segera ganti password!

---

## Update Website (Setelah Perubahan)

### Di Komputer Lokal

```bash
git add .
git commit -m "Deskripsi perubahan"
git push origin main
```

### Di Server (cPanel Terminal atau SSH)

```bash
cd ~/public_html
git pull origin main
composer install --no-dev
php spark cache:clear
```

---

## Troubleshooting Cepat

### ❌ Error 404 di semua halaman

```bash
# Pastikan .htaccess ada
ls -la .htaccess public/.htaccess

# Jika tidak ada, buat file .htaccess di root:
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ public/index.php/$1 [L]
```

### ❌ Database connection failed

- Cek kredensial di `.env`
- Pastikan database sudah dibuat
- Cek hostname (biasanya `localhost`)

### ❌ Permission denied

```bash
chmod -R 777 writable/
chmod -R 755 public/uploads/
```

### ❌ Halaman blank/error 500

```bash
# Cek error log
tail -f writable/logs/log-*.php

# Set environment ke development untuk lihat error
# Edit .env: CI_ENVIRONMENT = development
```

---

## Bantuan Lebih Lanjut

📖 **Dokumentasi Lengkap**:

- `README.md` - Informasi umum
- `DEPLOYMENT.md` - Panduan deployment detail
- `DEPLOYMENT_CHECKLIST.md` - Checklist lengkap

🆘 **Butuh Bantuan?**

- Cek file `DEPLOYMENT.md` untuk troubleshooting lengkap
- Hubungi developer team

---

**Selamat! Website Anda sudah online! 🎉**
