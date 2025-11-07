@echo off
REM ========================================
REM Iniciar Stripe CLI para Webhooks Locais
REM Autor: Rafael Dias - doisr.com.br
REM Data: 06/11/2025
REM ========================================

echo.
echo ========================================
echo   STRIPE CLI - WEBHOOK LISTENER
echo ========================================
echo.

REM Verificar se Stripe CLI está instalado
where stripe >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo [ERRO] Stripe CLI nao encontrado!
    echo.
    echo Instale o Stripe CLI:
    echo https://stripe.com/docs/stripe-cli
    echo.
    echo Ou execute manualmente se ja estiver instalado:
    echo "C:\caminho\para\stripe.exe" listen --forward-to http://localhost/conectcorretores/planos/webhook
    echo.
    pause
    exit /b 1
)

echo [INFO] Iniciando Stripe CLI...
echo.
echo URL do Webhook: http://localhost/conectcorretores/planos/webhook
echo.
echo ========================================
echo IMPORTANTE:
echo ========================================
echo.
echo 1. Copie o WEBHOOK SECRET que aparecera abaixo
echo 2. Cole em: application/config/stripe.php
echo 3. Procure por: stripe_webhook_secret_test
echo.
echo ========================================
echo.

REM Iniciar listener
stripe listen --forward-to http://localhost/conectcorretores/planos/webhook

echo.
echo [INFO] Stripe CLI encerrado.
pause
