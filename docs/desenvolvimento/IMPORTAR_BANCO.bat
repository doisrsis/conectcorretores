@echo off
cls
echo ========================================
echo   Importar Banco de Dados
echo   ConectCorretores
echo ========================================
echo.

echo IMPORTANTE: O MySQL deve estar rodando no XAMPP!
echo.
pause

echo.
echo Importando banco de dados...
echo.

C:\xampp\mysql\bin\mysql.exe -u root -p < database\schema.sql

if %errorlevel% equ 0 (
    echo.
    echo ========================================
    echo   SUCESSO!
    echo ========================================
    echo.
    echo Banco de dados importado com sucesso!
    echo.
    echo Credenciais de teste criadas:
    echo.
    echo Admin:
    echo   Email: admin@conectcorretores.com
    echo   Senha: password
    echo.
    echo ========================================
) else (
    echo.
    echo ========================================
    echo   ERRO!
    echo ========================================
    echo.
    echo Nao foi possivel importar o banco.
    echo.
    echo Verifique se:
    echo 1. MySQL esta rodando no XAMPP
    echo 2. Usuario root existe
    echo 3. Senha esta correta
    echo.
)

echo.
pause
