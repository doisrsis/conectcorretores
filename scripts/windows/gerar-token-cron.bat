@echo off
:: ========================================
:: Gerar Token de Segurança para Cron Jobs
:: Autor: Rafael Dias - doisr.com.br
:: Data: 08/11/2025
:: ========================================

echo.
echo ========================================
echo   GERAR TOKEN DE SEGURANCA - CRON JOBS
echo ========================================
echo.

:: Gerar token aleatório de 32 bytes (64 caracteres hex)
php -r "echo 'TOKEN GERADO: ' . bin2hex(random_bytes(32)) . PHP_EOL;"

echo.
echo ========================================
echo INSTRUCOES:
echo ========================================
echo.
echo 1. Copie o token gerado acima
echo 2. Adicione em application/config/config.php:
echo    $config['cron_token'] = 'SEU_TOKEN_AQUI';
echo.
echo 3. Use este token nos cron jobs do cPanel
echo.
echo ========================================
echo.

pause
