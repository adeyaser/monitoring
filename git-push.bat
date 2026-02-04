@echo off
REM Push to Remote Repository Script
REM This script helps push your code to GitHub/GitLab/Bitbucket

echo ========================================
echo Push to Remote Repository
echo ========================================
echo.

REM Check if Git is initialized
if not exist ".git" (
    echo ERROR: Git not initialized!
    echo Please run git-init.bat first
    echo.
    pause
    exit /b 1
)

echo Current Git status:
git status
echo.

REM Ask for remote URL if not set
git remote get-url origin >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo No remote repository configured.
    echo.
    echo Please create a repository on:
    echo - GitHub: https://github.com/new
    echo - GitLab: https://gitlab.com/projects/new
    echo - Bitbucket: https://bitbucket.org/repo/create
    echo.
    set /p REPO_URL="Enter your repository URL: "
    
    git remote add origin %REPO_URL%
    if %ERRORLEVEL% NEQ 0 (
        echo ERROR: Failed to add remote
        pause
        exit /b 1
    )
    echo OK - Remote added
    echo.
)

echo Current remote:
git remote -v
echo.

set /p CONFIRM="Push to remote repository? (y/n): "
if /i not "%CONFIRM%"=="y" (
    echo Cancelled
    pause
    exit /b 0
)

echo.
echo Pushing to remote...
git push -u origin main
if %ERRORLEVEL% NEQ 0 (
    echo.
    echo Push failed. Trying with 'master' branch...
    git branch -M main
    git push -u origin main
)

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ========================================
    echo SUCCESS! Code pushed to remote
    echo ========================================
) else (
    echo.
    echo ========================================
    echo ERROR! Push failed
    echo ========================================
    echo.
    echo Common issues:
    echo 1. Authentication failed - Check your credentials
    echo 2. Repository doesn't exist - Create it first
    echo 3. Branch mismatch - Try: git push -u origin master
)

echo.
pause
