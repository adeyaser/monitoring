@echo off
REM Git Initialization and First Commit Script
REM This script will initialize Git, add all files, and create first commit

echo ========================================
echo Git Initialization - Dashboard Monitoring
echo ========================================
echo.

REM Check if Git is installed
git --version >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Git is not installed!
    echo Please install Git from: https://git-scm.com/download/win
    echo.
    pause
    exit /b 1
)

echo Step 1: Initializing Git repository...
git init
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Failed to initialize Git
    pause
    exit /b 1
)
echo OK - Git initialized
echo.

echo Step 2: Configuring Git user (if not set)...
git config user.name >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    set /p GIT_NAME="Enter your name: "
    git config user.name "%GIT_NAME%"
)
git config user.email >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    set /p GIT_EMAIL="Enter your email: "
    git config user.email "%GIT_EMAIL%"
)
echo OK - Git user configured
echo.

echo Step 3: Adding all files to Git...
git add .
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Failed to add files
    pause
    exit /b 1
)
echo OK - Files added
echo.

echo Step 4: Creating initial commit...
git commit -m "Initial commit - Dashboard Monitoring v1.0"
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Failed to create commit
    pause
    exit /b 1
)
echo OK - Initial commit created
echo.

echo ========================================
echo SUCCESS! Git repository initialized
echo ========================================
echo.
echo Next steps:
echo 1. Create a repository on GitHub/GitLab/Bitbucket
echo 2. Run: git remote add origin [your-repo-url]
echo 3. Run: git push -u origin main
echo.
echo Or use the push-to-github.bat script
echo.
pause
