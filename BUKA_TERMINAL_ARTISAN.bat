@echo off
title Terminal Konsol Artisan Laravel (Portable)
color 0A

:: 1. Deteksi Lokasi Folder PHP
set "PHP_DIR="
if exist "%~dp0php\php.exe" (
    set "PHP_DIR=%~dp0php"
) else if exist "%~dp0..\php\php.exe" (
    set "PHP_DIR=%~dp0..\php"
)

:: 2. Deteksi Lokasi Folder Aplikasi Laravel
set "APP_DIR="
if exist "%~dp0app\artisan" (
    set "APP_DIR=%~dp0app"
) else if exist "%~dp0artisan" (
    set "APP_DIR=%~dp0"
) else if exist "%~dp0..\app\artisan" (
    set "APP_DIR=%~dp0..\app"
)

set "PATH=%PHP_DIR%;%PHP_DIR%\ext;%PATH%"
cd /d "%APP_DIR%"

echo =====================================================================
echo              KONSOL ARTISAN LARAVEL (PORTABLE PHP)
echo =====================================================================
echo PHP Version :
"%PHP_DIR%\php.exe" -v
echo.
echo Anda dapat mengetikkan perintah artisan langsung, contoh:
echo   php artisan route:list
echo   php artisan migrate:status
echo   php artisan db:seed
echo =====================================================================
echo.

cmd /k
