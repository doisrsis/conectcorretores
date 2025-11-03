-- ============================================
-- FIX: Corrigir tabela users - AUTO_INCREMENT
-- Autor: Rafael Dias - doisr.com.br
-- Data: 02/11/2025
-- ============================================

-- PASSO 1: Fazer backup dos dados
CREATE TABLE users_backup AS SELECT * FROM users;

-- PASSO 2: Deletar registros com ID = 0 (duplicados/inválidos)
-- ATENÇÃO: Isso vai remover os usuários criados com ID = 0
-- Se quiser manter, pule este passo e ajuste manualmente depois
DELETE FROM users WHERE id = 0;

-- PASSO 3: Resetar AUTO_INCREMENT para começar após o último ID válido
-- Descobrir o maior ID atual
SELECT @max_id := IFNULL(MAX(id), 0) FROM users;

-- PASSO 4: Recriar a tabela com AUTO_INCREMENT correto
ALTER TABLE users MODIFY COLUMN id INT(11) NOT NULL AUTO_INCREMENT;

-- PASSO 5: Definir próximo AUTO_INCREMENT
SET @sql = CONCAT('ALTER TABLE users AUTO_INCREMENT = ', @max_id + 1);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- PASSO 6: Verificar estrutura
SHOW CREATE TABLE users;

-- PASSO 7: Verificar dados
SELECT id, nome, email, role, created_at FROM users ORDER BY id;

-- ============================================
-- RESULTADO ESPERADO:
-- - Campo id com AUTO_INCREMENT
-- - Próximos usuários receberão ID 4, 5, 6...
-- - Sem registros com ID = 0
-- ============================================
