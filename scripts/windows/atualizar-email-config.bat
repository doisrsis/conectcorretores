@echo off
REM ========================================
REM Script: Atualizar Configuração de Email
REM Autor: Rafael Dias - doisr.com.br
REM Data: 08/11/2025
REM ========================================

echo.
echo ========================================
echo  Atualizando Configuracao de Email
echo ========================================
echo.

REM Ir para o diretório do projeto
cd /d "%~dp0..\.."

REM Verificar se o arquivo temporário existe
if not exist "TEMP_email_config.php" (
    echo [ERRO] Arquivo TEMP_email_config.php nao encontrado!
    pause
    exit /b 1
)

REM Copiar para application/config/email.php
echo [1/3] Copiando configuracao atualizada...
copy /Y "TEMP_email_config.php" "application\config\email.php"

if errorlevel 1 (
    echo [ERRO] Falha ao copiar arquivo!
    pause
    exit /b 1
)

echo [OK] Arquivo copiado com sucesso!
echo.

REM Deletar arquivo temporário
echo [2/3] Removendo arquivo temporario...
del "TEMP_email_config.php"

if errorlevel 1 (
    echo [AVISO] Nao foi possivel deletar o arquivo temporario
) else (
    echo [OK] Arquivo temporario removido!
)

echo.
echo [3/3] Configuracao atualizada!
echo.
echo ========================================
echo  CONFIGURACAO SMTP ATUALIZADA:
echo ========================================
echo  Servidor: mail.conectcorretores.com.br
echo  Porta: 465 (SSL)
echo  Usuario: noreply@conectcorretores.com.br
echo ========================================
echo.
echo Teste o envio de emails em:
echo http://localhost/conectcorretores/test_email
echo.
echo Pressione qualquer tecla para fechar...
pause > nul
