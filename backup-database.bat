@echo off
REM Database Export Script for Dashboard Monitoring
REM Usage: Run this script to backup your database before deployment

echo ========================================
echo Dashboard Monitoring - Database Backup
echo ========================================
echo.

REM Configuration
set DB_HOST=localhost
set DB_USER=root
set DB_PASS=
set DB_NAME=dashboard_monitoring
set BACKUP_DIR=database
set TIMESTAMP=%date:~-4%%date:~3,2%%date:~0,2%_%time:~0,2%%time:~3,2%%time:~6,2%
set TIMESTAMP=%TIMESTAMP: =0%
set BACKUP_FILE=%BACKUP_DIR%\backup_%TIMESTAMP%.sql

echo Creating backup...
echo Database: %DB_NAME%
echo Output: %BACKUP_FILE%
echo.

REM Export database
if "%DB_PASS%"=="" (
    mysqldump -h %DB_HOST% -u %DB_USER% %DB_NAME% > %BACKUP_FILE%
) else (
    mysqldump -h %DB_HOST% -u %DB_USER% -p%DB_PASS% %DB_NAME% > %BACKUP_FILE%
)

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ========================================
    echo SUCCESS! Database backup created
    echo File: %BACKUP_FILE%
    echo ========================================
) else (
    echo.
    echo ========================================
    echo ERROR! Backup failed
    echo Please check your database credentials
    echo ========================================
)

echo.
pause
