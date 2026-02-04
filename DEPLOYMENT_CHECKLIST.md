# 🚀 Deployment Checklist - Dashboard Monitoring

## Pre-Deployment (Local)

### 1. Backup Database

- [ ] Run `backup-database.bat`
- [ ] Verify backup file created in `database/` folder
- [ ] Test backup by importing to test database (optional)

### 2. Code Preparation

- [ ] Test all features locally
- [ ] Clear cache: `php spark cache:clear`
- [ ] Check for errors in `writable/logs/`
- [ ] Update version number (if applicable)

### 3. Environment Configuration

- [ ] Verify `.env.example` has all required settings
- [ ] Ensure `.env` is in `.gitignore` (already done ✓)
- [ ] Document any new environment variables

### 4. Git Setup

- [ ] Run `git-init.bat` (if first time)
- [ ] Review files to be committed: `git status`
- [ ] Verify `.gitignore` excludes sensitive files
- [ ] Create repository on GitHub/GitLab/Bitbucket

### 5. Push to Repository

- [ ] Run `git-push.bat` OR manually:

  ```bash
  git remote add origin [your-repo-url]
  git push -u origin main
  ```

- [ ] Verify all files uploaded to remote repository

## Deployment to Hosting

### Method A: Via Git (Recommended)

#### SSH Access

- [ ] Login to server via SSH
- [ ] Navigate to web root: `cd ~/public_html`
- [ ] Clone repository: `git clone [repo-url] .`
- [ ] Install dependencies: `composer install --no-dev --optimize-autoloader`
- [ ] Copy environment: `cp .env.example .env`
- [ ] Edit `.env` with production settings
- [ ] Set permissions:

  ```bash
  chmod -R 755 .
  chmod -R 777 writable/
  chmod 600 .env
  ```

#### cPanel Git Deploy

- [ ] Login to cPanel
- [ ] Go to "Git Version Control"
- [ ] Click "Create" and enter repository details
- [ ] After clone, access Terminal in cPanel
- [ ] Run: `composer install --no-dev`
- [ ] Setup `.env` file

### Method B: Via FTP/File Manager

- [ ] Upload all files except:
  - `.env` (create on server)
  - `.git/` folder
  - `writable/cache/*`
  - `writable/logs/*`
  - `writable/session/*`
  - `vendor/` (if will run composer on server)

- [ ] On server:
  - [ ] Create `.env` from `.env.example`
  - [ ] Edit `.env` with production credentials
  - [ ] Run `composer install` (if available)
  - [ ] Set folder permissions

## Database Setup

- [ ] Create database in cPanel/phpMyAdmin
- [ ] Create database user with full privileges
- [ ] Import database:
  - Via phpMyAdmin: Upload SQL file
  - Via SSH: `mysql -u user -p database < backup.sql`
- [ ] Update database credentials in `.env`

## Configuration

### Update .env for Production

- [ ] `CI_ENVIRONMENT = production`
- [ ] `app.baseURL = 'https://yourdomain.com/'`
- [ ] Database credentials (hostname, database, username, password)
- [ ] Generate encryption key: `php spark key:generate`

### Verify .htaccess

- [ ] Check `.htaccess` exists in root
- [ ] Check `.htaccess` exists in `public/`
- [ ] Verify `mod_rewrite` is enabled

### Set Permissions

```bash
# Directories
chmod 755 app/
chmod 755 public/
chmod 777 writable/
chmod 755 public/uploads/

# Files
chmod 644 .htaccess
chmod 600 .env
```

## Post-Deployment Testing

### Functionality Tests

- [ ] Access website URL
- [ ] Test login with default credentials
- [ ] Change admin password immediately
- [ ] Test dashboard loads correctly
- [ ] Test database connection
- [ ] Verify all menu items work
- [ ] Test data entry forms
- [ ] Test import/export features
- [ ] Check file upload functionality
- [ ] Test reports generation

### Security Checks

- [ ] Verify `.env` is not accessible via browser
- [ ] Check `writable/` is not web-accessible
- [ ] Test that error messages don't expose sensitive info
- [ ] Verify HTTPS is working (if SSL installed)
- [ ] Check CORS settings (if applicable)

### Performance Checks

- [ ] Test page load times
- [ ] Verify assets (CSS/JS) are loading
- [ ] Check database query performance
- [ ] Test with multiple concurrent users (if possible)

## SSL/HTTPS Setup

- [ ] Install SSL certificate (Let's Encrypt recommended)
- [ ] Update `app.baseURL` to use `https://`
- [ ] Add HTTPS redirect in `.htaccess`:

  ```apache
  RewriteCond %{HTTPS} off
  RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
  ```

- [ ] Test HTTPS access

## Monitoring Setup

- [ ] Setup error logging
- [ ] Configure log rotation
- [ ] Setup uptime monitoring (e.g., UptimeRobot)
- [ ] Configure email notifications for errors (optional)
- [ ] Setup backup schedule

## Documentation

- [ ] Document production URL
- [ ] Document database credentials (secure location)
- [ ] Document any custom configurations
- [ ] Update README.md with production notes
- [ ] Share access credentials with team (securely)

## Rollback Plan

### If Deployment Fails

- [ ] Keep backup of previous version
- [ ] Document rollback procedure
- [ ] Test rollback in staging (if available)

### Quick Rollback

```bash
# Via Git
git reset --hard [previous-commit-hash]
git push -f origin main

# Via Backup
# Restore database and files from backup
```

## Maintenance

### Regular Tasks

- [ ] Weekly: Check logs for errors
- [ ] Weekly: Test backup restoration
- [ ] Monthly: Update dependencies: `composer update`
- [ ] Monthly: Review security patches
- [ ] Quarterly: Performance audit

### Update Procedure

```bash
# Local
git add .
git commit -m "Update: description"
git push origin main

# Server
cd /path/to/app
git pull origin main
composer install --no-dev
php spark cache:clear
```

## Support Information

- **Repository**: [Your Git Repository URL]
- **Production URL**: [Your Website URL]
- **Developer**: [Your Name/Team]
- **Documentation**: See README.md and DEPLOYMENT.md

---

## Quick Command Reference

```bash
# Backup database
mysqldump -u user -p database > backup.sql

# Import database
mysql -u user -p database < backup.sql

# Clear cache
php spark cache:clear

# Set permissions
chmod -R 777 writable/

# Git pull and update
git pull && composer install --no-dev && php spark cache:clear
```

---

**Last Updated**: 2026-02-04
**Version**: 1.0
