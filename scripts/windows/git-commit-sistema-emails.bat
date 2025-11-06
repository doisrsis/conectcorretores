@echo off
REM Script para commit do sistema de emails
REM Autor: Rafael Dias - doisr.com.br
REM Data: 06/11/2025

echo ========================================
echo Git Commit - Sistema de Emails
echo ========================================
echo.

cd /d "%~dp0..\.."

echo Verificando status...
echo.
git status

echo.
echo ========================================
echo Arquivos a serem commitados:
echo ========================================
echo.
echo NOVOS ARQUIVOS:
echo - Sistema de emails completo (config, library, templates)
echo - Controller de testes
echo - Documentacao completa
echo - Webhook secret configurado
echo.
echo MODIFICADOS:
echo - Configuracoes Stripe atualizadas
echo - README.md atualizado
echo.

set /p confirma=Deseja continuar com o commit? (S/N): 

if /i "%confirma%" NEQ "S" (
    echo.
    echo Commit cancelado.
    pause
    exit
)

echo.
echo Adicionando arquivos ao stage...
echo.

REM Adicionar novos arquivos
git add application/config/email.php
git add application/libraries/Email_lib.php
git add application/views/emails/
git add application/controllers/Test_email.php
git add docs/desenvolvimento/SISTEMA-EMAILS-IMPLEMENTADO.md
git add docs/desenvolvimento/TESTAR-EMAILS.md
git add docs/desenvolvimento/CONFIGURAR-WEBHOOK-STRIPE-CLI.md

REM Adicionar arquivos modificados
git add application/config/stripe.php
git add docs/README.md

echo.
echo Criando commit...
echo.

git commit -m "feat: Implementar sistema completo de emails transacionais

- Adicionar configuracao de email (SMTP Gmail/SendGrid)
- Criar biblioteca Email_lib com 10 metodos de envio
- Criar layout base responsivo para emails
- Criar 10 templates de emails:
  * Boas-vindas
  * Assinatura ativada
  * Pagamento confirmado
  * Lembrete de renovacao
  * Falha no pagamento
  * Plano vencido
  * Upgrade confirmado
  * Downgrade confirmado
  * Cancelamento confirmado
  * Recuperacao de senha
- Adicionar controller de testes (Test_email)
- Documentar sistema completo
- Atualizar chaves Stripe e webhook secret
- Atualizar README com nova documentacao

Autor: Rafael Dias - doisr.com.br
Data: 06/11/2025"

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ========================================
    echo Commit realizado com sucesso!
    echo ========================================
    echo.
    echo Deseja fazer push para o repositorio remoto?
    set /p push=Push para origin? (S/N): 
    
    if /i "!push!" EQU "S" (
        echo.
        echo Fazendo push...
        git push origin main
        
        if %ERRORLEVEL% EQU 0 (
            echo.
            echo ========================================
            echo Push realizado com sucesso!
            echo ========================================
        ) else (
            echo.
            echo ========================================
            echo ERRO ao fazer push!
            echo ========================================
        )
    )
) else (
    echo.
    echo ========================================
    echo ERRO ao criar commit!
    echo ========================================
)

echo.
pause
