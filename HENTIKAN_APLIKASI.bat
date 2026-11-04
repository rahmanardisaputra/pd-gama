@echo off
title Hentikan Aplikasi Buku Induk Siswa
color 4F

echo =====================================================================
echo                MENGHENTIKAN SERVER APLIKASI
echo =====================================================================
echo.

echo Menghentikan proses PHP server...
taskkill /F /IM php.exe /T >nul 2>&1

echo.
echo [BERHASIL] Aplikasi Buku Induk & Leger Siswa telah dihentikan.
echo.
timeout /t 3 >nul
exit
