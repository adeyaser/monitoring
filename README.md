# Dashboard Monitoring - Terminal Operations

Dashboard monitoring untuk operasional terminal pelabuhan dengan fitur manajemen data operasional, laporan, dan visualisasi real-time.

## Features

- 📊 **Dashboard Real-time** - Monitoring data operasional terminal
- 📈 **Data Operasional** - Manajemen YOR, Container, Throughput
- 🚢 **Vessel Schedule** - Jadwal kapal dan berth management
- 📑 **Reporting** - Laporan harian dan bulanan
- 👥 **ACL Management** - User, Groups, Permissions, Menus
- 📥 **Import/Export** - Excel import/export untuk bulk data
- ⚙️ **Settings** - Konfigurasi aplikasi

## Tech Stack

- **Framework**: CodeIgniter 4
- **Database**: MySQL/MariaDB
- **Frontend**: Bootstrap 5, jQuery, Chart.js
- **Libraries**: SheetJS (Excel), SweetAlert2, DataTables

## Requirements

- PHP 7.4 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Composer
- Apache/Nginx with mod_rewrite enabled

## Installation

### 1. Clone Repository

```bash
git clone <repository-url>
cd dashboard_monitoring
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Edit .env file with your configuration
nano .env
```

Update the following in `.env`:
- `CI_ENVIRONMENT` = production
- `app.baseURL` = your domain URL
- Database credentials
- Generate encryption key: `php spark key:generate`

### 4. Database Setup

```bash
# Import database
mysql -u username -p database_name < database/dashboard_monitoring.sql

# Or run migrations if available
php spark migrate
```

### 5. Set Permissions

```bash
# Make writable directory writable
chmod -R 777 writable/
chmod -R 777 public/uploads/
```

### 6. Apache Configuration

Ensure `.htaccess` is enabled and `mod_rewrite` is active.

For subdirectory installation, update `app.baseURL` in `.env`:
```
app.baseURL = 'https://yourdomain.com/dashboard_monitoring/'
```

For root domain:
```
app.baseURL = 'https://yourdomain.com/'
```

## Deployment to Hosting

### Via Git

1. **Initialize Git** (if not already):
```bash
git init
git add .
git commit -m "Initial commit"
```

2. **Add Remote Repository**:
```bash
git remote add origin <your-git-repo-url>
git push -u origin main
```

3. **On Server** (via SSH):
```bash
cd /path/to/public_html
git clone <your-git-repo-url> .
composer install --no-dev --optimize-autoloader
cp .env.example .env
nano .env  # Configure for production
chmod -R 777 writable/
```

### Via FTP/cPanel

1. Upload all files except:
   - `.env` (configure on server)
   - `writable/` contents (except index.html)
   - `vendor/` (run composer on server)

2. On server:
   - Create `.env` from `.env.example`
   - Run `composer install`
   - Set permissions for `writable/`

## Default Login

After database import, use:
- **Username**: admin
- **Password**: admin123

⚠️ **Important**: Change default password immediately after first login!

## Security Checklist

- [ ] Change `CI_ENVIRONMENT` to `production` in `.env`
- [ ] Generate new encryption key
- [ ] Update database credentials
- [ ] Change default admin password
- [ ] Set proper file permissions (755 for directories, 644 for files)
- [ ] Disable directory listing
- [ ] Enable HTTPS/SSL
- [ ] Configure CORS if needed

## Folder Structure

```
dashboard_monitoring/
├── app/
│   ├── Controllers/
│   ├── Models/
│   ├── Views/
│   └── Config/
├── public/
│   ├── assets/
│   └── uploads/
├── writable/
│   ├── cache/
│   ├── logs/
│   └── session/
├── vendor/
├── .env
├── .env.example
└── .gitignore
```

## Troubleshooting

### 404 Errors
- Check `.htaccess` exists in root and `public/`
- Verify `mod_rewrite` is enabled
- Check `app.baseURL` in `.env`

### Database Connection Error
- Verify database credentials in `.env`
- Check database server is running
- Ensure database exists

### Permission Denied
```bash
chmod -R 777 writable/
chmod -R 755 public/uploads/
```

## Support

For issues and questions, please contact the development team.

## License

Proprietary - All rights reserved
