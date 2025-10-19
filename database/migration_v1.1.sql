-- ========================================
-- Migration v1.1 - Simplificação do CRUD de Imóveis
-- Autor: Rafael Dias - doisr.com.br
-- Data: 18/10/2025
-- ========================================

USE `corretor_saas`;

-- ========================================
-- 1. Criar Tabela de Estados
-- ========================================
CREATE TABLE IF NOT EXISTS `estados` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uf` CHAR(2) NOT NULL,
  `nome` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uf` (`uf`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- 2. Popular Tabela de Estados
-- ========================================
INSERT INTO `estados` (`uf`, `nome`) VALUES
('AC', 'Acre'),
('AL', 'Alagoas'),
('AP', 'Amapá'),
('AM', 'Amazonas'),
('BA', 'Bahia'),
('CE', 'Ceará'),
('DF', 'Distrito Federal'),
('ES', 'Espírito Santo'),
('GO', 'Goiás'),
('MA', 'Maranhão'),
('MT', 'Mato Grosso'),
('MS', 'Mato Grosso do Sul'),
('MG', 'Minas Gerais'),
('PA', 'Pará'),
('PB', 'Paraíba'),
('PR', 'Paraná'),
('PE', 'Pernambuco'),
('PI', 'Piauí'),
('RJ', 'Rio de Janeiro'),
('RN', 'Rio Grande do Norte'),
('RS', 'Rio Grande do Sul'),
('RO', 'Rondônia'),
('RR', 'Roraima'),
('SC', 'Santa Catarina'),
('SP', 'São Paulo'),
('SE', 'Sergipe'),
('TO', 'Tocantins')
ON DUPLICATE KEY UPDATE nome = VALUES(nome);

-- ========================================
-- 3. Criar Tabela de Cidades
-- ========================================
CREATE TABLE IF NOT EXISTS `cidades` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `estado_id` INT UNSIGNED NOT NULL,
  `nome` VARCHAR(100) NOT NULL,
  `ibge_code` VARCHAR(10) NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `estado_id` (`estado_id`),
  UNIQUE KEY `unique_cidade` (`estado_id`, `nome`),
  CONSTRAINT `fk_cidades_estado` FOREIGN KEY (`estado_id`) REFERENCES `estados` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- 4. Ajustar Tabela de Imóveis
-- ========================================

-- Adicionar novos campos
ALTER TABLE `imoveis` 
ADD COLUMN `cep` VARCHAR(10) NULL AFTER `user_id`,
ADD COLUMN `estado_id` INT UNSIGNED NULL AFTER `cep`,
ADD COLUMN `cidade_id` INT UNSIGNED NULL AFTER `estado_id`,
ADD COLUMN `link` VARCHAR(500) NULL AFTER `descricao`,
ADD COLUMN `whatsapp` VARCHAR(20) NULL AFTER `telefone`;

-- Modificar campos existentes
ALTER TABLE `imoveis`
MODIFY COLUMN `tipo_imovel` VARCHAR(50) NOT NULL COMMENT 'Apartamento, Casa, Condomínio, Terreno, Comercial, Fazenda, Sítio, Outros',
MODIFY COLUMN `bairro` VARCHAR(100) NULL,
MODIFY COLUMN `quartos` INT NOT NULL DEFAULT 1 COMMENT 'Quantidade de quartos',
MODIFY COLUMN `vagas` INT NOT NULL DEFAULT 1 COMMENT 'Quantidade de vagas';

-- Remover campos desnecessários
ALTER TABLE `imoveis`
DROP COLUMN IF EXISTS `endereco`,
DROP COLUMN IF EXISTS `numero`,
DROP COLUMN IF EXISTS `complemento`,
DROP COLUMN IF EXISTS `suites`,
DROP COLUMN IF EXISTS `banheiros`,
DROP COLUMN IF EXISTS `area_total`,
DROP COLUMN IF EXISTS `condominio`,
DROP COLUMN IF EXISTS `iptu`,
DROP COLUMN IF EXISTS `caracteristicas`,
DROP COLUMN IF EXISTS `imagens`;

-- Adicionar Foreign Keys
ALTER TABLE `imoveis`
ADD CONSTRAINT `fk_imoveis_estado` FOREIGN KEY (`estado_id`) REFERENCES `estados` (`id`) ON DELETE SET NULL,
ADD CONSTRAINT `fk_imoveis_cidade` FOREIGN KEY (`cidade_id`) REFERENCES `cidades` (`id`) ON DELETE SET NULL;

-- Adicionar índices para performance
ALTER TABLE `imoveis`
ADD INDEX `idx_cep` (`cep`),
ADD INDEX `idx_tipo_imovel` (`tipo_imovel`);

-- ========================================
-- 5. Atualizar View de Imóveis Completa
-- ========================================
DROP VIEW IF EXISTS `v_imoveis_completa`;

CREATE VIEW `v_imoveis_completa` AS
SELECT 
    i.*,
    e.uf AS estado_uf,
    e.nome AS estado_nome,
    c.nome AS cidade_nome,
    u.nome AS corretor_nome,
    u.email AS corretor_email,
    u.telefone AS corretor_telefone,
    u.whatsapp AS corretor_whatsapp
FROM imoveis i
LEFT JOIN estados e ON i.estado_id = e.id
LEFT JOIN cidades c ON i.cidade_id = c.id
INNER JOIN users u ON i.user_id = u.id;

-- ========================================
-- 6. Atualizar Trigger de Valor m²
-- ========================================
DROP TRIGGER IF EXISTS `tr_imoveis_valor_m2`;
DROP TRIGGER IF EXISTS `tr_imoveis_valor_m2_update`;

DELIMITER $$

CREATE TRIGGER `tr_imoveis_valor_m2` BEFORE INSERT ON `imoveis`
FOR EACH ROW
BEGIN
    IF NEW.area_privativa > 0 THEN
        SET NEW.valor_m2 = NEW.preco / NEW.area_privativa;
    ELSE
        SET NEW.valor_m2 = 0;
    END IF;
END$$

CREATE TRIGGER `tr_imoveis_valor_m2_update` BEFORE UPDATE ON `imoveis`
FOR EACH ROW
BEGIN
    IF NEW.area_privativa > 0 THEN
        SET NEW.valor_m2 = NEW.preco / NEW.area_privativa;
    ELSE
        SET NEW.valor_m2 = 0;
    END IF;
END$$

DELIMITER ;

-- ========================================
-- 7. Comentários nas Tabelas
-- ========================================
ALTER TABLE `estados` COMMENT = 'Estados brasileiros (UF)';
ALTER TABLE `cidades` COMMENT = 'Cidades brasileiras (populadas dinamicamente via ViaCEP)';

-- ========================================
-- FIM DA MIGRATION v1.1
-- ========================================

-- Para aplicar esta migration:
-- mysql -u root -p corretor_saas < database/migration_v1.1.sql
