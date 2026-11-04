@echo off
title Reset & Seeder Database Buku Induk
color 6F

echo =====================================================================
echo             RESET DAN SEEDER DATABASE (BUKU INDUK)
echo =====================================================================
echo.
echo PERINGATAN: Tindakan ini akan mengosongkan database dan mengisi
echo ulang master referensi Dapodik, akun admin, serta data contoh.
echo.
set /p confirm="Ketik Y lalu Enter untuk melanjutkan (atau tutup jika batal): "
if /i not "%confirm%"=="Y" exit /b

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

echo.
echo Menjalankan fresh migration dan seeder...
"%PHP_DIR%\php.exe" artisan migrate:fresh --seed --force

echo.
echo =====================================================================
echo [SELESAI] Database berhasil di-reset dan diisi ulang!
echo Akun Admin : admin@sekolah.sch.id / password
echo =====================================================================
echo.
pause
