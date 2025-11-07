@echo off
REM ========================================
REM Corrigir Tabela password_resets
REM Autor: Rafael Dias - doisr.com.br
REM Data: 07/11/2025
REM ========================================

echo.
echo ========================================
echo   CORRIGIR TABELA PASSWORD_RESETS
echo ========================================
echo.

cd /d "%~dp0..\.."

echo [INFO] Este script vai adicionar colunas faltantes na tabela password_resets
echo.

REM Verificar se arquivo existe
if not exist "database\fixes\fix_password_resets_table.sql" (
    echo [ERRO] Arquivo SQL nao encontrado!
    pause
    exit /b 1
)

echo O que este script faz:
echo 1. Verifica estrutura atual
echo 2. Adiciona coluna expires_at (se nao existir)
echo 3. Adiciona coluna used (se nao existir)
echo 4. Adiciona coluna used_at (se nao existir)
echo 5. Adiciona indices necessarios
echo.
echo SEGURO: Nao remove dados existentes
echo.

set /p confirma="Deseja continuar? (S/N): "
if /i not "%confirma%"=="S" (
    echo.
    echo [INFO] Operacao cancelada pelo usuario.
    pause
    exit /b 0
)

echo.
echo ========================================
echo EXECUTANDO CORRECAO...
echo ========================================
echo.

REM Executar via MySQL
mysql -u root corretor_saas < database\fixes\fix_password_resets_table.sql

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ========================================
    echo CORRECAO CONCLUIDA COM SUCESSO!
    echo ========================================
    echo.
    echo Tabela password_resets corrigida.
    echo.
    echo Agora voce pode testar a recuperacao de senha:
    echo http://localhost/conectcorretores/password/forgot
    echo.
) else (
    echo.
    echo ========================================
    echo ERRO NA CORRECAO!
    echo ========================================
    echo.
    echo Tente executar manualmente via phpMyAdmin:
    echo database\fixes\fix_password_resets_table.sql
    echo.
)

pause
