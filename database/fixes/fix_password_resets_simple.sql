-- ========================================
-- Fix Simples: Recriar Tabela password_resets
-- Autor: Rafael Dias - doisr.com.br
-- Data: 07/11/2025
-- ========================================

-- ========================================
-- OPÇÃO 1: RECRIAR TABELA (PERDE DADOS)
-- ========================================

-- Deletar tabela existente
DROP TABLE IF EXISTS `password_resets`;

-- Criar tabela correta
CREATE TABLE `password_resets` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NULL DEFAULT NULL,
  `used` tinyint(1) NOT NULL DEFAULT 0,
  `used_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `token` (`token`),
  KEY `expires_at` (`expires_at`),
  KEY `used` (`used`),
  CONSTRAINT `fk_password_resets_user` 
    FOREIGN KEY (`user_id`) 
    REFERENCES `users` (`id`) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Índice composto para busca eficiente
CREATE INDEX `idx_token_valid` ON `password_resets` (`token`, `used`, `expires_at`);

-- Comentário
ALTER TABLE `password_resets` COMMENT = 'Tokens de recuperação de senha';

-- ========================================
-- VERIFICAÇÃO
-- ========================================

DESCRIBE `password_resets`;

SHOW INDEX FROM `password_resets`;

-- ========================================
-- RESULTADO ESPERADO
-- ========================================

/*
Field         | Type              | Null | Key | Default           | Extra
------------- | ----------------- | ---- | --- | ----------------- | ----------------
id            | int(11) unsigned  | NO   | PRI | NULL              | auto_increment
user_id       | int(11) unsigned  | NO   | MUL | NULL              |
token         | varchar(255)      | NO   | MUL | NULL              |
created_at    | timestamp         | NO   |     | CURRENT_TIMESTAMP |
expires_at    | timestamp         | YES  | MUL | NULL              |
used          | tinyint(1)        | NO   | MUL | 0                 |
used_at       | timestamp         | YES  |     | NULL              |
*/
