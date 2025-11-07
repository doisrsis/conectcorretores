@echo off
REM ========================================
REM Verificar Configuracao de Logs
REM Autor: Rafael Dias - doisr.com.br
REM Data: 06/11/2025
REM ========================================

echo.
echo ========================================
echo   VERIFICAR LOGS DO SISTEMA
echo ========================================
echo.

cd /d "%~dp0..\.."

echo [INFO] Verificando pasta de logs...
echo.

if exist "application\logs" (
    echo [OK] Pasta application\logs existe
    echo.
    
    echo [INFO] Arquivos de log:
    dir application\logs\*.php /b 2>nul
    if %ERRORLEVEL% NEQ 0 (
        echo [AVISO] Nenhum arquivo de log encontrado
    )
    echo.
    
    echo [INFO] Permissoes da pasta:
    icacls application\logs
    echo.
) else (
    echo [ERRO] Pasta application\logs NAO existe!
    echo.
)

echo ========================================
echo PROXIMOS PASSOS:
echo ========================================
echo.
echo 1. Editar: application\config\config.php
echo 2. Procurar por: log_threshold
echo 3. Alterar para: 4
echo.
echo Exemplo:
echo $config['log_threshold'] = 4;
echo.
echo ========================================
echo.

pause
