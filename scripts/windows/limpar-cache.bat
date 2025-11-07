@echo off
REM ========================================
REM Limpar Cache do CodeIgniter
REM Autor: Rafael Dias - doisr.com.br
REM Data: 07/11/2025
REM ========================================

echo.
echo ========================================
echo   LIMPAR CACHE DO CODEIGNITER
echo ========================================
echo.

cd /d "%~dp0..\.."

echo [INFO] Limpando cache do CodeIgniter...
echo.

REM Limpar cache de views
if exist "application\cache\*.*" (
    del /Q application\cache\*.*
    echo [OK] Cache de views limpo
) else (
    echo [INFO] Pasta de cache vazia
)

echo.
echo ========================================
echo CACHE LIMPO!
echo ========================================
echo.
echo Proximos passos:
echo 1. Atualizar a pagina no navegador (Ctrl+F5)
echo 2. Testar novamente
echo.

pause
