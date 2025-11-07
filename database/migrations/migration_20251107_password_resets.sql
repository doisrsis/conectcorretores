-- ========================================
-- Migration: Criar Tabela de Reset de Senha
-- Autor: Rafael Dias - doisr.com.br
-- Data: 07/11/2025
-- ========================================

-- ========================================
-- CRIAR TABELA password_resets
-- ========================================

CREATE TABLE IF NOT EXISTS `password_resets` (
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

-- ========================================
-- ÍNDICES ADICIONAIS
-- ========================================

-- Índice composto para busca eficiente de tokens válidos
CREATE INDEX `idx_token_valid` ON `password_resets` (`token`, `used`, `expires_at`);

-- ========================================
-- COMENTÁRIOS
-- ========================================

ALTER TABLE `password_resets` COMMENT = 'Tokens de recuperação de senha';

-- ========================================
-- VERIFICAÇÃO
-- ========================================

-- Verificar se tabela foi criada
SHOW CREATE TABLE `password_resets`;

-- Verificar estrutura
DESCRIBE `password_resets`;

-- ========================================
-- ROLLBACK (se necessário)
-- ========================================

-- Para desfazer esta migration:
-- DROP TABLE IF EXISTS `password_resets`;

-- ========================================
-- NOTAS
-- ========================================

-- Estrutura da tabela:
-- - id: Identificador único do token
-- - user_id: ID do usuário que solicitou reset
-- - token: Token único gerado (hash)
-- - created_at: Data/hora de criação
-- - expires_at: Data/hora de expiração (24h após criação)
-- - used: Flag indicando se token foi usado
-- - used_at: Data/hora em que token foi usado

-- Segurança:
-- - Tokens expiram em 24 horas
-- - Tokens são de uso único (flag 'used')
-- - Tokens são deletados em cascata se usuário for deletado
-- - Índices otimizam busca de tokens válidos

-- ========================================
