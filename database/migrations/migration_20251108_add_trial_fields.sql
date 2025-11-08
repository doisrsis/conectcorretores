-- ========================================
-- Migration: Adicionar Campos de Trial
-- Autor: Rafael Dias - doisr.com.br
-- Data: 08/11/2025
-- Descrição: Adiciona campos para gerenciar período de teste gratuito
-- ========================================

-- Adicionar coluna is_trial (indica se é período de teste)
ALTER TABLE `subscriptions` 
ADD COLUMN `is_trial` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Se é período de teste gratuito' AFTER `status`;

-- Adicionar coluna trial_ends_at (data de fim do trial)
ALTER TABLE `subscriptions` 
ADD COLUMN `trial_ends_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Data de término do período de teste' AFTER `is_trial`;

-- Adicionar coluna converted_from_trial (indica se foi convertido de trial)
ALTER TABLE `subscriptions` 
ADD COLUMN `converted_from_trial` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Se foi convertido de trial para pago' AFTER `trial_ends_at`;

-- Adicionar coluna converted_at (data da conversão)
ALTER TABLE `subscriptions` 
ADD COLUMN `converted_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Data da conversão de trial para pago' AFTER `converted_from_trial`;

-- Adicionar índice para trial_ends_at (para buscar trials expirando)
ALTER TABLE `subscriptions` 
ADD INDEX `idx_trial_ends_at` (`trial_ends_at`);

-- Adicionar índice composto para buscar trials ativos
ALTER TABLE `subscriptions` 
ADD INDEX `idx_trial_active` (`is_trial`, `status`, `trial_ends_at`);

-- ========================================
-- Atualizar status enum para incluir 'trial'
-- ========================================
ALTER TABLE `subscriptions` 
MODIFY COLUMN `status` ENUM('ativa','cancelada','pendente','expirada','trial') NOT NULL DEFAULT 'pendente';

-- ========================================
-- Comentários das colunas
-- ========================================

-- is_trial: 
--   0 = Assinatura paga normal
--   1 = Período de teste gratuito

-- trial_ends_at:
--   NULL = Não é trial ou trial já convertido
--   TIMESTAMP = Data/hora de término do trial

-- converted_from_trial:
--   0 = Nunca foi trial ou ainda está em trial
--   1 = Foi convertido de trial para pago

-- converted_at:
--   NULL = Não foi convertido
--   TIMESTAMP = Data/hora da conversão

-- status:
--   'trial' = Em período de teste
--   'ativa' = Assinatura paga ativa
--   'pendente' = Aguardando pagamento
--   'cancelada' = Cancelada pelo usuário
--   'expirada' = Período expirado

-- ========================================
-- Rollback (caso necessário)
-- ========================================

-- Para reverter esta migration:
/*
ALTER TABLE `subscriptions` DROP INDEX `idx_trial_active`;
ALTER TABLE `subscriptions` DROP INDEX `idx_trial_ends_at`;
ALTER TABLE `subscriptions` DROP COLUMN `converted_at`;
ALTER TABLE `subscriptions` DROP COLUMN `converted_from_trial`;
ALTER TABLE `subscriptions` DROP COLUMN `trial_ends_at`;
ALTER TABLE `subscriptions` DROP COLUMN `is_trial`;
ALTER TABLE `subscriptions` MODIFY COLUMN `status` ENUM('ativa','cancelada','pendente','expirada') NOT NULL DEFAULT 'pendente';
*/
