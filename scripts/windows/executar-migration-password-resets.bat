@echo off
REM ========================================
REM Executar Migration: Password Resets
REM Autor: Rafael Dias - doisr.com.br
REM Data: 07/11/2025
REM ========================================

echo.
echo ========================================
echo   MIGRATION: PASSWORD RESETS
echo ========================================
echo.

cd /d "%~dp0..\.."

echo [INFO] Esta migration vai criar a tabela 'password_resets'
echo.
echo Tabela: password_resets
echo - Armazena tokens de recuperacao de senha
echo - Tokens expiram em 24 horas
echo - Tokens sao de uso unico
echo.

REM Verificar se arquivo existe
if not exist "database\migrations\migration_20251107_password_resets.sql" (
    echo [ERRO] Arquivo de migration nao encontrado!
    pause
    exit /b 1
)

echo ========================================
echo IMPORTANTE:
echo ========================================
echo.
echo Esta migration vai:
echo 1. Criar tabela password_resets
echo 2. Criar indices para otimizacao
echo 3. Criar foreign key para users
echo.
echo Nao e necessario backup (tabela nova)
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
echo EXECUTANDO MIGRATION...
echo ========================================
echo.

REM Executar migration via PHP
php -r "try { $db = new PDO('mysql:host=localhost;dbname=corretor_saas;charset=utf8mb4', 'root', ''); $sql = file_get_contents('database/migrations/migration_20251107_password_resets.sql'); $statements = array_filter(array_map('trim', explode(';', $sql)), function($s) { return !empty($s) && strpos($s, '--') !== 0; }); foreach ($statements as $stmt) { if (stripos($stmt, 'CREATE TABLE') !== false || stripos($stmt, 'CREATE INDEX') !== false || stripos($stmt, 'ALTER TABLE') !== false) { $db->exec($stmt); echo '[OK] ' . substr($stmt, 0, 50) . '...' . PHP_EOL; } } echo PHP_EOL . '[SUCESSO] Migration executada!' . PHP_EOL; } catch (Exception $e) { echo '[ERRO] ' . $e->getMessage() . PHP_EOL; exit(1); }"

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ========================================
    echo MIGRATION CONCLUIDA COM SUCESSO!
    echo ========================================
    echo.
    echo Tabela 'password_resets' criada.
    echo.
    echo Proximos passos:
    echo 1. Criar model Password_reset_model
    echo 2. Criar controller Password
    echo 3. Criar views de recuperacao
    echo 4. Testar fluxo completo
    echo.
) else (
    echo.
    echo ========================================
    echo ERRO NA MIGRATION!
    echo ========================================
    echo.
    echo Verifique:
    echo 1. Conexao com banco de dados
    echo 2. Permissoes do usuario
    echo 3. Tabela users existe
    echo.
    echo Ou execute manualmente via phpMyAdmin:
    echo database\migrations\migration_20251107_password_resets.sql
    echo.
)

pause
