@echo off
cls
echo ========================================
echo   ConectCorretores - CodeIgniter 3
echo   Autor: Rafael Dias - doisr.com.br
echo ========================================
echo.

echo [1/3] Verificando PHP 8.3...
C:\xampp\php83\php.exe -v | findstr "PHP 8.3"
if errorlevel 1 (
    echo ERRO: PHP 8.3 nao encontrado!
    pause
    exit /b 1
)
echo PHP 8.3 OK!
echo.

echo [2/3] Verificando banco de dados...
echo.
echo IMPORTANTE: Certifique-se de que:
echo 1. MySQL esta rodando no XAMPP
echo 2. Banco 'corretor_saas' foi criado
echo 3. Schema foi importado (database/schema.sql)
echo.
pause

echo [3/3] Iniciando servidor...
echo.
echo ========================================
echo   Servidor rodando em:
echo   http://localhost:8083
echo ========================================
echo.
echo Pressione Ctrl+C para parar
echo.

cd /d C:\xampp\htdocs\conectcorretores
C:\xampp\php83\php.exe -S localhost:8083
