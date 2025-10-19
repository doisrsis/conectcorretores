-- ========================================
-- EXECUTAR ESTE SQL AGORA NO PHPMYADMIN
-- ========================================

-- Adicionar coluna stripe_customer_id na tabela users
ALTER TABLE `users` 
ADD COLUMN `stripe_customer_id` VARCHAR(255) NULL DEFAULT NULL COMMENT 'ID do cliente no Stripe' AFTER `whatsapp`,
ADD INDEX `idx_stripe_customer_id` (`stripe_customer_id`);
