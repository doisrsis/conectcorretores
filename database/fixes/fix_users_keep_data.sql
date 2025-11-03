-- ============================================
-- FIX: Corrigir tabela users MANTENDO os dados
-- Autor: Rafael Dias - doisr.com.br
-- Data: 02/11/2025
-- ============================================

-- PASSO 1: Fazer backup
CREATE TABLE users_backup AS SELECT * FROM users;

-- PASSO 2: Atribuir IDs únicos aos registros com ID = 0
-- Começar do ID 4 (próximo disponível)

SET @new_id = 3; -- Último ID válido atual

-- Atualizar cada registro com ID = 0
UPDATE users 
SET id = (@new_id := @new_id + 1) 
WHERE id = 0 
ORDER BY created_at ASC;

-- PASSO 3: Verificar se ainda há ID = 0
SELECT COUNT(*) as registros_com_id_zero FROM users WHERE id = 0;

-- PASSO 4: Reativar AUTO_INCREMENT
ALTER TABLE users MODIFY COLUMN id INT(11) NOT NULL AUTO_INCREMENT;

-- PASSO 5: Definir próximo AUTO_INCREMENT
SELECT @max_id := MAX(id) FROM users;
SET @sql = CONCAT('ALTER TABLE users AUTO_INCREMENT = ', @max_id + 1);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- PASSO 6: Verificar resultado
SELECT id, nome, email, role, created_at FROM users ORDER BY id;

-- PASSO 7: Verificar estrutura da tabela
SHOW CREATE TABLE users;

-- ============================================
-- RESULTADO ESPERADO:
-- ID 1: Administrador
-- ID 2: Rafael de Andrade Dias (rafaeldiaswebdev@gmail.com)
-- ID 3: Doisr Sistemas
-- ID 4: Rafael de Andrade Dias (rafaeldiastecinfo@gmail.com)
-- ID 5: Rodrigo Barbosa (rodrigo@gmail.com)
-- ID 6: Rodrigo Barbosa (rodrigobarbosa@gmail.com)
-- ID 7: Rodrigo Dias (rodrigobarbosa2@gmail.com)
-- ============================================

-- IMPORTANTE: Após executar, os usuários precisarão fazer login novamente
-- porque o user_id na sessão estará desatualizado
