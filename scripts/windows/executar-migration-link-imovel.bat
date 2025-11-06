@echo off
REM Script para executar migration link_imovel
REM Autor: Rafael Dias - doisr.com.br
REM Data: 06/11/2025

echo ========================================
echo Migration: Adicionar Link do Imovel
echo ========================================
echo.

echo ATENCAO: Certifique-se de ter feito backup do banco!
echo.
echo Pressione qualquer tecla para continuar ou Ctrl+C para cancelar...
pause > nul

echo.
echo Executando migration...
echo.

cd /d "%~dp0..\.."

mysql -u root -p conectcorretores < database\migrations\migration_20251103_add_link_imovel.sql

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ========================================
    echo Migration executada com sucesso!
    echo ========================================
    echo.
    echo Proximos passos:
    echo 1. Verificar estrutura da tabela imoveis
    echo 2. Testar cadastro de imoveis
    echo 3. Verificar se campo Link do Imovel aparece
) else (
    echo.
    echo ========================================
    echo ERRO ao executar migration!
    echo ========================================
    echo.
    echo Verifique:
    echo 1. MySQL esta rodando
    echo 2. Usuario e senha corretos
    echo 3. Banco 'conectcorretores' existe
)

echo.
pause
