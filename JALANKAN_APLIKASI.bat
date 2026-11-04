@echo off
title Aplikasi Buku Induk dan Leger Siswa (Portable)
color 1F

echo =====================================================================
echo       MEMPERSIAPKAN SISTEM BUKU INDUK DAN LEGER SISWA PORTABLE
echo =====================================================================
echo.

:: 1. Deteksi Lokasi Folder PHP
set "PHP_DIR="
if exist "%~dp0php\php.exe" (
    set "PHP_DIR=%~dp0php"
) else if exist "%~dp0..\php\php.exe" (
    set "PHP_DIR=%~dp0..\php"
)

if "%PHP_DIR%"=="" (
    color 4F
    echo [ERROR] Folder PHP tidak ditemukan!
    echo Pastikan folder 'php' berada di satu folder dengan file ini atau sejajar dengan folder 'app'.
    echo.
    pause
    exit /b
)

:: Tambahkan PHP ke PATH sementara agar DLL SQLite dan OpenSSL terbaca
set "PATH=%PHP_DIR%;%PHP_DIR%\ext;%PATH%"

:: 2. Deteksi Lokasi Folder Aplikasi Laravel
set "APP_DIR="
if exist "%~dp0app\artisan" (
    set "APP_DIR=%~dp0app"
) else if exist "%~dp0artisan" (
    set "APP_DIR=%~dp0"
) else if exist "%~dp0..\app\artisan" (
    set "APP_DIR=%~dp0..\app"
)

if "%APP_DIR%"=="" (
    color 4F
    echo [ERROR] File aplikasi Laravel (artisan) tidak ditemukan!
    echo Pastikan folder aplikasi diletakkan di dalam folder 'app' atau sejajar dengan file ini.
    echo.
    pause
    exit /b
)

cd /d "%APP_DIR%"

:: 3. Verifikasi Konfigurasi .env & SQLite
if not exist ".env" (
    if exist ".env.example" (
        echo Menyalin file konfigurasi .env...
        copy /y ".env.example" ".env" >nul
    )
)

:: Pastikan APP_KEY terisi
findstr /C:"APP_KEY=base64" ".env" >nul
if errorlevel 1 (
    echo Membuat Application Key...
    "%PHP_DIR%\php.exe" artisan key:generate --force >nul 2>&1
)

if not exist "database\database.sqlite" (
    echo Membuat file database SQLite awal...
    if not exist "database" mkdir database
    type nul > "database\database.sqlite"
)

:: Pastikan direktori storage dan cache tersedia
if not exist "storage\framework\cache\data" mkdir "storage\framework\cache\data" 2>nul
if not exist "storage\framework\sessions" mkdir "storage\framework\sessions" 2>nul
if not exist "storage\framework\views" mkdir "storage\framework\views" 2>nul
if not exist "storage\logs" mkdir "storage\logs" 2>nul

:: 4. Jalankan Migrasi & Seeder jika database masih baru
for %%F in ("database\database.sqlite") do set "DB_SIZE=%%~zF"
if "%DB_SIZE%"=="0" (
    echo Database kosong terdeteksi, mengisi data awal Dapodik...
    "%PHP_DIR%\php.exe" artisan migrate:fresh --seed --force >nul 2>&1
) else (
    "%PHP_DIR%\php.exe" artisan migrate --force >nul 2>&1
)

:: 5. Buka Browser Secara Otomatis setelah server aktif (tunda 2 detik di background)
start "" cmd /c "timeout /t 2 /nobreak >nul & start http://127.0.0.1:8000"

cls
color 2F
echo =====================================================================
echo      APLIKASI BUKU INDUK DAN LEGER SISWA PORTABLE (AKTIF)
echo =====================================================================
echo.
echo  Aplikasi dapat diakses melalui browser pada alamat:
echo  --^> http://127.0.0.1:8000
echo.
echo  Akun Login Administrator Default:
echo  Email    : admin@sekolah.sch.id
echo  Password : password
echo.
echo  Database : SQLite (%APP_DIR%\database\database.sqlite)
echo  PHP Ver  : 8.3 (Portable x64)
echo.
echo ---------------------------------------------------------------------
echo  PERHATIAN:
echo  JANGAN TUTUP JENDELA INI selama Anda masih menggunakan aplikasi!
echo  Untuk mematikan: Tutup jendela ini atau jalankan HENTIKAN_APLIKASI.bat
echo =====================================================================
echo.

"%PHP_DIR%\php.exe" artisan serve --host=127.0.0.1 --port=8000
