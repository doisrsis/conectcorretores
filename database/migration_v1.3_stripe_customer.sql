-- ========================================
-- Migration v1.3 - Adicionar stripe_customer_id na tabela users
-- Autor: Rafael Dias - doisr.com.br
-- Data: 19/10/2025
-- ========================================

USE `corretor_saas`;

-- ========================================
-- Adicionar coluna stripe_customer_id
-- ========================================
ALTER TABLE `users` 
ADD COLUMN `stripe_customer_id` VARCHAR(255) NULL DEFAULT NULL COMMENT 'ID do cliente no Stripe' AFTER `whatsapp`,
ADD INDEX `idx_stripe_customer_id` (`stripe_customer_id`);

-- ========================================
-- FIM DA MIGRATION v1.3
-- ========================================

-- Para aplicar esta migration:
-- mysql -u root -p corretor_saas < database/migration_v1.3_stripe_customer.sql
-- Ou execute no phpMyAdmin
