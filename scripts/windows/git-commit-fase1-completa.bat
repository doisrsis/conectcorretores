@echo off
REM Script para commit da Fase 1 completa
REM Autor: Rafael Dias - doisr.com.br
REM Data: 06/11/2025

echo ========================================
echo Git Commit - Fase 1 Completa
echo ========================================
echo.

cd /d "%~dp0..\.."

echo Verificando status...
echo.
git status

echo.
echo ========================================
echo FASE 1 - MELHORIAS CRITICAS
echo ========================================
echo.
echo 1. Webhook Secret configurado
echo 2. Migration SQL executada
echo 3. Sistema de emails completo
echo.
echo ARQUIVOS:
echo - 20+ arquivos novos
echo - Templates de configuracao (.example)
echo - Documentacao completa
echo - Scripts auxiliares
echo.
echo CREDENCIAIS PROTEGIDAS:
echo - email.php e stripe.php no .gitignore
echo - Apenas .example.php serao commitados
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

REM Sistema de emails
git add application/config/email.example.php
git add application/libraries/Email_lib.php
git add application/views/emails/
git add application/controllers/Test_email.php

REM Configuracoes Stripe (apenas example)
git add application/config/stripe.example.php

REM Documentacao
git add docs/desenvolvimento/ANALISE-SISTEMA-ASSINATURAS.md
git add docs/desenvolvimento/SISTEMA-EMAILS-IMPLEMENTADO.md
git add docs/desenvolvimento/TESTAR-EMAILS.md
git add docs/desenvolvimento/CONFIGURAR-WEBHOOK-SECRET.md
git add docs/desenvolvimento/CONFIGURAR-WEBHOOK-STRIPE-CLI.md
git add docs/desenvolvimento/CONFIGURAR-CREDENCIAIS.md
git add docs/desenvolvimento/EXECUTAR-MIGRATION-LINK-IMOVEL.md
git add docs/desenvolvimento/ROADMAP-SISTEMA-EMAILS.md
git add docs/desenvolvimento/GIT-COMMIT-SISTEMA-EMAILS.md
git add docs/RELATORIO-CLIENTE-SISTEMA-ASSINATURAS.md
git add docs/README.md

REM Scripts
git add scripts/windows/executar-migration-link-imovel.bat
git add scripts/windows/git-commit-sistema-emails.bat
git add scripts/windows/git-commit-fase1-completa.bat

REM .gitignore atualizado
git add .gitignore

echo.
echo Criando commit...
echo.

git commit -m "feat: Implementar Fase 1 - Melhorias Criticas do Sistema

FASE 1 COMPLETA - Sistema de Assinaturas e Emails

## Webhook Secret (Seguranca)
- Configurar webhook secret do Stripe
- Adicionar validacao de assinatura
- Documentar processo com Stripe CLI
- Testar todos os eventos (200 OK)

## Migration SQL
- Adicionar campo link_imovel
- Remover campos de contato duplicados
- Criar scripts de execucao
- Documentar rollback

## Sistema de Emails Transacionais
- Criar configuracao SMTP (Gmail/SendGrid)
- Implementar biblioteca Email_lib
- Criar layout responsivo para emails
- Implementar 10 templates profissionais:
  * Boas-vindas
  * Assinatura ativada
  * Pagamento confirmado
  * Lembrete de renovacao (7 dias)
  * Falha no pagamento
  * Plano vencido
  * Upgrade confirmado
  * Downgrade confirmado
  * Cancelamento confirmado
  * Recuperacao de senha
- Criar controller de testes
- Testar todos os emails (100% funcional)

## Documentacao
- Analise completa do sistema de assinaturas
- Relatorio para cliente (nao-tecnico)
- Guias de configuracao
- Roadmaps e checklists
- Scripts auxiliares

## Seguranca
- Proteger credenciais com .gitignore
- Criar arquivos .example para templates
- Documentar processo de configuracao
- Separar credenciais do codigo

## Estatisticas
- Arquivos criados: 20+
- Linhas de codigo: ~2.500
- Tempo de desenvolvimento: 4 horas
- Funcionalidades completas: 3

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
