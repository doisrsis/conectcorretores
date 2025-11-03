/**
 * Migration: Adicionar campo link_imovel na tabela imoveis
 * 
 * Autor: Rafael Dias - doisr.com.br
 * Data: 03/11/2025
 * 
 * Descrição:
 * - Adiciona coluna link_imovel (URL opcional)
 * - Remove colunas de contato (link, telefone, whatsapp)
 * - Contatos serão pegos do cadastro do corretor
 */

-- Adicionar coluna link_imovel
ALTER TABLE imoveis 
ADD COLUMN link_imovel VARCHAR(500) NULL AFTER descricao
COMMENT 'Link para página do imóvel no site do corretor';

-- Remover colunas de contato (agora vêm do corretor)
ALTER TABLE imoveis 
DROP COLUMN IF EXISTS link,
DROP COLUMN IF EXISTS telefone,
DROP COLUMN IF EXISTS whatsapp;

-- Verificar resultado
SELECT 
    COLUMN_NAME,
    COLUMN_TYPE,
    IS_NULLABLE,
    COLUMN_COMMENT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'imoveis'
AND COLUMN_NAME IN ('link_imovel', 'link', 'telefone', 'whatsapp')
ORDER BY ORDINAL_POSITION;
