-- =============================================
-- Migration: Sistema de Logs de Atividade
-- Autor: Rafael Dias - doisr.com.br
-- Data: 11/11/2025 23:28
-- =============================================

-- Criar tabela de logs de atividade
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT 'ID do usuário que executou a ação (NULL para ações do sistema)',
  `user_name` varchar(255) DEFAULT NULL COMMENT 'Nome do usuário (para histórico)',
  `action` varchar(100) NOT NULL COMMENT 'Tipo de ação (login, logout, create, update, delete, etc)',
  `module` varchar(50) NOT NULL COMMENT 'Módulo afetado (users, imoveis, planos, settings, etc)',
  `record_id` int(11) DEFAULT NULL COMMENT 'ID do registro afetado',
  `description` text COMMENT 'Descrição detalhada da ação',
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'Endereço IP do usuário',
  `user_agent` varchar(255) DEFAULT NULL COMMENT 'User Agent do navegador',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_action` (`action`),
  KEY `idx_module` (`module`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Logs de atividades do sistema';

-- Índice composto para consultas comuns
CREATE INDEX `idx_user_module_action` ON `activity_logs` (`user_id`, `module`, `action`);

-- Comentários nas colunas
ALTER TABLE `activity_logs` 
  MODIFY COLUMN `action` varchar(100) NOT NULL COMMENT 'login, logout, create, update, delete, view, export, import, etc',
  MODIFY COLUMN `module` varchar(50) NOT NULL COMMENT 'users, imoveis, planos, subscriptions, settings, cupons, etc';
