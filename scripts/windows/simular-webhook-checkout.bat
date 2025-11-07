@echo off
REM ========================================
REM Simular Webhook de Checkout
REM Autor: Rafael Dias - doisr.com.br
REM Data: 07/11/2025
REM ========================================

echo.
echo ========================================
echo   SIMULAR WEBHOOK DO STRIPE
echo ========================================
echo.

echo [INFO] Este script simula um evento de checkout.session.completed
echo.
echo IMPORTANTE:
echo - Stripe CLI deve estar rodando
echo - Webhook secret deve estar configurado
echo.

pause

echo.
echo [INFO] Enviando evento simulado...
echo.

stripe trigger checkout.session.completed

echo.
echo ========================================
echo VERIFICAR:
echo ========================================
echo.
echo 1. Stripe CLI deve mostrar o evento
echo 2. Verificar logs em: application\logs\log-2025-11-07.php
echo 3. Procurar por: "WEBHOOK RECEBIDO"
echo 4. Verificar se email foi enviado
echo.
echo ATENCAO:
echo - Dados serao fake (IDs nao existem no banco)
echo - Pode dar erro se user_id ou plan_id nao existirem
echo - Use apenas para testar se webhook chega
echo.
pause
