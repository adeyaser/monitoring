# Cara Menghilangkan index.php dari URL

## ✅ Sudah Dikonfigurasi

Konfigurasi untuk menghilangkan `index.php` dari URL sudah dilakukan:

1. ✅ `app/Config/App.php` - `$indexPage` diset ke `''` (string kosong)
2. ✅ `.htaccess` di root folder - Redirect ke `public/`
3. ✅ `public/.htaccess` - URL rewriting rules

## 🔧 Cara Mengaktifkan di XAMPP (Windows)

### Langkah 1: Aktifkan mod_rewrite

1. Buka file `httpd.conf`:
   - Lokasi: `D:\xampp-82\apache\conf\httpd.conf`
   - Atau via XAMPP Control Panel → Apache → Config → httpd.conf

2. Cari baris ini (sekitar line 145):

   ```apache
   #LoadModule rewrite_module modules/mod_rewrite.so
   ```

3. Hapus tanda `#` di depannya menjadi:

   ```apache
   LoadModule rewrite_module modules/mod_rewrite.so
   ```

4. Save file

### Langkah 2: Izinkan .htaccess Override

1. Di file `httpd.conf` yang sama, cari bagian:

   ```apache
   <Directory "D:/xampp-82/htdocs">
       AllowOverride None
   ```

2. Ubah `None` menjadi `All`:

   ```apache
   <Directory "D:/xampp-82/htdocs">
       AllowOverride All
   ```

3. Save file

### Langkah 3: Restart Apache

1. Buka XAMPP Control Panel
2. Klik **Stop** pada Apache
3. Tunggu beberapa detik
4. Klik **Start** pada Apache

### Langkah 4: Test

Sekarang URL Anda akan menjadi:

❌ **Sebelum**: `http://localhost/dashboard_monitoring/index.php/dashboard`

✅ **Sesudah**: `http://localhost/dashboard_monitoring/dashboard`

## 🌐 Untuk Production/Hosting

Biasanya hosting sudah mengaktifkan `mod_rewrite` secara default. Pastikan:

1. ✅ File `.htaccess` ter-upload
2. ✅ `mod_rewrite` aktif (biasanya sudah)
3. ✅ `AllowOverride All` diset di Apache config

### cPanel

Di cPanel, mod_rewrite biasanya sudah aktif. Jika masih ada masalah:

1. Buka **File Manager**
2. Pastikan file `.htaccess` ada di:
   - Root folder
   - Folder `public/`
3. Check permissions: 644

### Troubleshooting

#### ❌ Error 500 Internal Server Error

**Penyebab**: Syntax error di `.htaccess` atau mod_rewrite tidak aktif

**Solusi**:

```bash
# Cek error log Apache
tail -f /path/to/apache/logs/error.log

# Atau di XAMPP:
# D:\xampp-82\apache\logs\error.log
```

#### ❌ Masih muncul index.php di URL

**Solusi**:

1. Clear browser cache
2. Pastikan `app/Config/App.php` → `$indexPage = ''`
3. Restart Apache
4. Test dengan browser incognito/private

#### ❌ 404 Not Found di semua halaman

**Penyebab**: `.htaccess` tidak dibaca atau mod_rewrite tidak aktif

**Solusi**:

1. Cek `AllowOverride All` di httpd.conf
2. Cek mod_rewrite aktif
3. Restart Apache

## 📝 Verifikasi mod_rewrite Aktif

Buat file `test.php` di root XAMPP:

```php
<?php
phpinfo();
```

Buka: `http://localhost/test.php`

Cari "Loaded Modules" → Harus ada `mod_rewrite`

## 🔗 URL Structure

Setelah konfigurasi, struktur URL akan menjadi:

```
http://localhost/dashboard_monitoring/
http://localhost/dashboard_monitoring/dashboard
http://localhost/dashboard_monitoring/login
http://localhost/dashboard_monitoring/operational/data
http://localhost/dashboard_monitoring/reports/daily
```

Tidak ada lagi `/index.php/` di tengah URL!

---

**Catatan**: Perubahan ini sudah di-commit dan di-push ke GitHub. Saat deploy ke hosting, pastikan mod_rewrite aktif (biasanya sudah default).
