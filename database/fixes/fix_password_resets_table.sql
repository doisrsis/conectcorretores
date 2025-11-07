-- ========================================
-- Fix: Adicionar Colunas Faltantes na Tabela password_resets
-- Autor: Rafael Dias - doisr.com.br
-- Data: 07/11/2025
-- ========================================

-- ========================================
-- VERIFICAR ESTRUTURA ATUAL
-- ========================================

DESCRIBE `password_resets`;

-- ========================================
-- ADICIONAR COLUNAS FALTANTES
-- ========================================

-- Verificar se coluna expires_at existe, se não, adicionar
SET @dbname = DATABASE();
SET @tablename = 'password_resets';
SET @columnname = 'expires_at';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN `expires_at` timestamp NULL DEFAULT NULL AFTER `created_at`;')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Verificar se coluna used existe, se não, adicionar
SET @columnname = 'used';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN `used` tinyint(1) NOT NULL DEFAULT 0 AFTER `expires_at`;')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Verificar se coluna used_at existe, se não, adicionar
SET @columnname = 'used_at';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN `used_at` timestamp NULL DEFAULT NULL AFTER `used`;')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- ========================================
-- ADICIONAR ÍNDICES SE NÃO EXISTIREM
-- ========================================

-- Índice para token
SET @indexname = 'token';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (index_name = @indexname)
  ) > 0,
  'SELECT 1',
  CONCAT('CREATE INDEX `', @indexname, '` ON `', @tablename, '` (`token`);')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Índice para expires_at
SET @indexname = 'expires_at';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (index_name = @indexname)
  ) > 0,
  'SELECT 1',
  CONCAT('CREATE INDEX `', @indexname, '` ON `', @tablename, '` (`expires_at`);')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Índice para used
SET @indexname = 'used';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (index_name = @indexname)
  ) > 0,
  'SELECT 1',
  CONCAT('CREATE INDEX `', @indexname, '` ON `', @tablename, '` (`used`);')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Índice composto para busca eficiente
SET @indexname = 'idx_token_valid';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (index_name = @indexname)
  ) > 0,
  'SELECT 1',
  CONCAT('CREATE INDEX `', @indexname, '` ON `', @tablename, '` (`token`, `used`, `expires_at`);')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- ========================================
-- VERIFICAR ESTRUTURA FINAL
-- ========================================

DESCRIBE `password_resets`;

-- ========================================
-- VERIFICAR ÍNDICES
-- ========================================

SHOW INDEX FROM `password_resets`;

-- ========================================
-- RESULTADO ESPERADO
-- ========================================

/*
Estrutura esperada:

Field         | Type              | Null | Key | Default           | Extra
------------- | ----------------- | ---- | --- | ----------------- | ----------------
id            | int(11) unsigned  | NO   | PRI | NULL              | auto_increment
user_id       | int(11) unsigned  | NO   | MUL | NULL              |
token         | varchar(255)      | NO   | MUL | NULL              |
created_at    | timestamp         | NO   |     | CURRENT_TIMESTAMP |
expires_at    | timestamp         | NO   | MUL | NULL              |
used          | tinyint(1)        | NO   | MUL | 0                 |
used_at       | timestamp         | YES  |     | NULL              |

Índices:
- PRIMARY (id)
- user_id (user_id)
- token (token)
- expires_at (expires_at)
- used (used)
- idx_token_valid (token, used, expires_at)
*/

-- ========================================
-- NOTAS
-- ========================================

/*
Este script:
1. Verifica se cada coluna existe antes de adicionar
2. Adiciona apenas colunas faltantes
3. Não remove ou modifica colunas existentes
4. Adiciona índices se não existirem
5. É seguro executar múltiplas vezes (idempotente)

Se a tabela foi criada sem as colunas corretas,
este script vai adicionar as colunas faltantes sem perder dados.
*/
