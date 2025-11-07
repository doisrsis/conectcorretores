@echo off
REM ========================================
REM Testar Webhook Manualmente
REM Autor: Rafael Dias - doisr.com.br
REM Data: 06/11/2025
REM ========================================

echo.
echo ========================================
echo   TESTE MANUAL DE WEBHOOK
echo ========================================
echo.

cd /d "%~dp0..\.."

echo [INFO] Este script simula um webhook do Stripe
echo.
echo Escolha o evento para testar:
echo.
echo 1. checkout.session.completed (Assinatura Ativada)
echo 2. invoice.payment_succeeded (Pagamento Confirmado)
echo 3. invoice.payment_failed (Falha no Pagamento)
echo.
set /p opcao="Digite o numero: "

if "%opcao%"=="1" (
    echo.
    echo [INFO] Testando checkout.session.completed...
    echo.
    stripe trigger checkout.session.completed
)

if "%opcao%"=="2" (
    echo.
    echo [INFO] Testando invoice.payment_succeeded...
    echo.
    stripe trigger invoice.payment_succeeded
)

if "%opcao%"=="3" (
    echo.
    echo [INFO] Testando invoice.payment_failed...
    echo.
    stripe trigger invoice.payment_failed
)

echo.
echo ========================================
echo VERIFICAR:
echo ========================================
echo.
echo 1. Stripe CLI deve mostrar [200] ou [400]
echo 2. Verificar logs em: application\logs\log-2025-11-06.php
echo 3. Verificar se email chegou
echo.
pause
