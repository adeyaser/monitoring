# Deployment Guide - Dashboard Monitoring

## Pre-Deployment Checklist

### 1. Backup Database

```bash
# Export database
mysqldump -u root -p dashboard_monitoring > database/backup_$(date +%Y%m%d).sql
```

### 2. Update Configuration

- [ ] Set `CI_ENVIRONMENT = production` in `.env`
- [ ] Update `app.baseURL` to production URL
- [ ] Generate new encryption key: `php spark key:generate`
- [ ] Update database credentials for production

### 3. Optimize for Production

```bash
# Clear cache
php spark cache:clear

# Optimize composer autoloader
composer install --no-dev --optimize-autoloader
```

## Deployment Methods

### Method 1: Git Deployment (Recommended)

#### A. First Time Setup

1. **Initialize Git Repository**

```bash
cd d:\xampp-82\htdocs\dashboard_monitoring
git init
git add .
git commit -m "Initial commit - Dashboard Monitoring v1.0"
```

1. **Create Repository on GitHub/GitLab/Bitbucket**
   - Go to GitHub.com (or GitLab/Bitbucket)
   - Create new repository: `dashboard-monitoring`
   - Don't initialize with README (we already have one)

2. **Push to Remote**

```bash
git remote add origin https://github.com/yourusername/dashboard-monitoring.git
git branch -M main
git push -u origin main
```

#### B. Deploy to Hosting

**Via SSH (Recommended):**

```bash
# SSH to your server
ssh user@yourserver.com

# Navigate to web root
cd /home/username/public_html

# Clone repository
git clone https://github.com/yourusername/dashboard-monitoring.git .

# Install dependencies
composer install --no-dev --optimize-autoloader

# Setup environment
cp .env.example .env
nano .env  # Edit with production settings

# Set permissions
chmod -R 755 .
chmod -R 777 writable/
chmod -R 755 public/uploads/

# Import database
mysql -u dbuser -p dbname < database/dashboard_monitoring.sql
```

**Via cPanel Git Deploy:**

1. Login to cPanel
2. Go to "Git Version Control"
3. Click "Create"
4. Enter repository URL
5. Set repository path to `public_html` or subdirectory
6. Click "Create"
7. After clone, run composer and setup .env

#### C. Update Deployment

```bash
# On your local machine
git add .
git commit -m "Update: description of changes"
git push origin main

# On server
cd /path/to/public_html
git pull origin main
composer install --no-dev --optimize-autoloader
php spark cache:clear
```

### Method 2: FTP/cPanel File Manager

1. **Prepare Files Locally**

```bash
# Create deployment package
composer install --no-dev --optimize-autoloader
php spark cache:clear
```

1. **Upload via FTP**
   - Upload all files EXCEPT:
     - `.env` (create on server)
     - `writable/cache/*`
     - `writable/logs/*`
     - `writable/session/*`
     - `.git/` folder

2. **On Server (via cPanel File Manager or SSH)**
   - Create `.env` from `.env.example`
   - Edit `.env` with production settings
   - Set folder permissions:
     - `writable/` → 777
     - `public/uploads/` → 755

3. **Import Database**
   - Via phpMyAdmin: Import SQL file
   - Via SSH: `mysql -u user -p database < backup.sql`

### Method 3: Automated Deployment Script

Create `deploy.sh` on server:

```bash
#!/bin/bash
cd /home/username/public_html
git pull origin main
composer install --no-dev --optimize-autoloader
php spark cache:clear
chmod -R 777 writable/
echo "Deployment completed at $(date)"
```

Make executable and run:

```bash
chmod +x deploy.sh
./deploy.sh
```

## Post-Deployment Steps

### 1. Verify Installation

- [ ] Access website URL
- [ ] Test login functionality
- [ ] Check database connection
- [ ] Verify file uploads work
- [ ] Test all main features

### 2. Security Hardening

```bash
# Remove public access to sensitive files
chmod 600 .env
chmod 644 .htaccess

# Ensure writable is not web-accessible
# Add to .htaccess in writable/:
# Deny from all
```

### 3. Setup SSL Certificate

- Use Let's Encrypt (free)
- Or install SSL via cPanel
- Update `app.baseURL` to use `https://`

### 4. Configure Cron Jobs (Optional)

For automated tasks:

```bash
# Example: Daily cleanup
0 2 * * * cd /path/to/app && php spark cache:clear
```

### 5. Setup Monitoring

- Enable error logging
- Monitor `writable/logs/`
- Setup uptime monitoring

## Environment-Specific Settings

### Development (.env)

```
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost/dashboard_monitoring/'
```

### Production (.env)

```
CI_ENVIRONMENT = production
app.baseURL = 'https://yourdomain.com/'
```

## Common Issues & Solutions

### Issue: 404 on all pages

**Solution:**

```bash
# Check .htaccess exists
ls -la .htaccess public/.htaccess

# Verify mod_rewrite is enabled
# Add to .htaccess if needed:
RewriteEngine On
```

### Issue: Database connection failed

**Solution:**

- Verify credentials in `.env`
- Check database server hostname (often `localhost` or `127.0.0.1`)
- Ensure database user has proper permissions

### Issue: Permission denied errors

**Solution:**

```bash
chmod -R 777 writable/
chown -R www-data:www-data writable/  # Linux
```

### Issue: Assets not loading

**Solution:**

- Check `app.baseURL` in `.env`
- Verify file paths in views
- Clear browser cache

## Rollback Procedure

### Via Git

```bash
# View commit history
git log --oneline

# Rollback to specific commit
git reset --hard <commit-hash>
git push -f origin main

# On server
git pull origin main --force
```

### Via Backup

```bash
# Restore database
mysql -u user -p database < backup_20260204.sql

# Restore files
cp -r backup_folder/* /path/to/public_html/
```

## Performance Optimization

1. **Enable OPcache** (php.ini):

```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
```

1. **Enable Gzip Compression** (.htaccess):

```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
</IfModule>
```

1. **Browser Caching** (.htaccess):

```apache
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

## Maintenance Mode

To enable maintenance:

1. Create `writable/maintenance.html`
2. Add to `.htaccess`:

```apache
RewriteCond %{REQUEST_URI} !^/maintenance.html$
RewriteCond %{REMOTE_ADDR} !^123\.456\.789\.000$
RewriteRule ^(.*)$ /maintenance.html [R=503,L]
```

## Support Contacts

- **Developer**: [Your Name]
- **Email**: [your-email@domain.com]
- **Documentation**: See README.md

---
Last Updated: 2026-02-04
