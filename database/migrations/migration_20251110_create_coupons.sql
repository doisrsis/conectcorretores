-- ========================================
-- Migration: Criar tabela de cupons de desconto
-- Autor: Rafael Dias - doisr.com.br
-- Data: 10/11/2025
-- Versão: 1.7.0
-- ========================================

-- Criar tabela de cupons
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL COMMENT 'Código do cupom (ex: BEMVINDO, PROMO20)',
  `stripe_coupon_id` varchar(255) DEFAULT NULL COMMENT 'ID do cupom no Stripe',
  `tipo` enum('percent','fixed') NOT NULL DEFAULT 'percent' COMMENT 'Tipo de desconto: percent (%) ou fixed (R$)',
  `valor` decimal(10,2) NOT NULL COMMENT 'Valor do desconto (20.00 para 20% ou R$ 20,00)',
  `duracao` enum('once','repeating','forever') NOT NULL DEFAULT 'once' COMMENT 'Duração: once (1x), repeating (N meses), forever (sempre)',
  `duracao_meses` int(11) DEFAULT NULL COMMENT 'Número de meses se duracao=repeating',
  `max_usos` int(11) DEFAULT NULL COMMENT 'Máximo de usos (NULL = ilimitado)',
  `usos_atuais` int(11) NOT NULL DEFAULT 0 COMMENT 'Quantidade de vezes que foi usado',
  `valido_de` date DEFAULT NULL COMMENT 'Data de início da validade',
  `valido_ate` date DEFAULT NULL COMMENT 'Data de fim da validade',
  `ativo` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = ativo, 0 = inativo',
  `descricao` text DEFAULT NULL COMMENT 'Descrição interna do cupom',
  `created_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'ID do admin que criou',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `idx_codigo_ativo` (`codigo`, `ativo`),
  KEY `idx_validade` (`valido_de`, `valido_ate`),
  KEY `idx_stripe_coupon_id` (`stripe_coupon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Cupons de desconto para assinaturas';

-- Criar tabela de histórico de uso de cupons
CREATE TABLE IF NOT EXISTS `coupon_usage` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `coupon_id` int(11) UNSIGNED NOT NULL COMMENT 'ID do cupom usado',
  `user_id` int(11) UNSIGNED NOT NULL COMMENT 'ID do usuário que usou',
  `subscription_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'ID da assinatura criada',
  `desconto_aplicado` decimal(10,2) NOT NULL COMMENT 'Valor do desconto aplicado',
  `valor_original` decimal(10,2) NOT NULL COMMENT 'Valor original da assinatura',
  `valor_final` decimal(10,2) NOT NULL COMMENT 'Valor final após desconto',
  `usado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_coupon_id` (`coupon_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_subscription_id` (`subscription_id`),
  KEY `idx_usado_em` (`usado_em`),
  CONSTRAINT `fk_coupon_usage_coupon` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_coupon_usage_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_coupon_usage_subscription` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Histórico de uso de cupons';

-- Inserir cupons de exemplo (opcional - comentado)
-- INSERT INTO `coupons` (`codigo`, `tipo`, `valor`, `duracao`, `descricao`, `ativo`) VALUES
-- ('BEMVINDO', 'percent', 20.00, 'once', 'Cupom de boas-vindas - 20% de desconto no primeiro mês', 1),
-- ('PROMO50', 'percent', 50.00, 'once', 'Promoção especial - 50% de desconto no primeiro mês', 1),
-- ('DESCONTO10', 'fixed', 10.00, 'once', 'R$ 10,00 de desconto no primeiro mês', 1);

-- ========================================
-- ROLLBACK (caso necessário)
-- ========================================
-- DROP TABLE IF EXISTS `coupon_usage`;
-- DROP TABLE IF EXISTS `coupons`;

-- ========================================
-- VERIFICAÇÃO
-- ========================================
-- SELECT * FROM coupons;
-- SELECT * FROM coupon_usage;
-- DESCRIBE coupons;
-- DESCRIBE coupon_usage;
