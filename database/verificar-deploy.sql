-- ===================================
-- Script de Verificação Pós-Deploy
-- ConectCorretores
-- 
-- Autor: Rafael Dias - doisr.com.br
-- Data: 19/10/2025
-- ===================================

-- Verificar se todas as tabelas existem
SELECT 'Verificando tabelas...' as status;

SELECT 
    TABLE_NAME,
    TABLE_ROWS,
    CREATE_TIME
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = DATABASE()
ORDER BY TABLE_NAME;

-- Verificar planos cadastrados
SELECT 'Verificando planos...' as status;

SELECT 
    id,
    nome,
    preco,
    tipo,
    stripe_price_id,
    ativo
FROM plans;

-- Verificar se há planos sem stripe_price_id
SELECT 'Planos sem Stripe Price ID:' as alerta;

SELECT 
    id,
    nome,
    tipo
FROM plans 
WHERE stripe_price_id IS NULL OR stripe_price_id = '';

-- Verificar usuários
SELECT 'Total de usuários:' as status, COUNT(*) as total FROM users;

-- Verificar assinaturas
SELECT 'Total de assinaturas:' as status, COUNT(*) as total FROM subscriptions;

-- Verificar assinaturas ativas
SELECT 'Assinaturas ativas:' as status, COUNT(*) as total 
FROM subscriptions 
WHERE status = 'ativa' AND data_fim >= CURDATE();

-- Verificar imóveis
SELECT 'Total de imóveis:' as status, COUNT(*) as total FROM imoveis;

-- Verificar estrutura da tabela users (se tem stripe_customer_id)
SELECT 'Verificando coluna stripe_customer_id na tabela users...' as status;

SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    IS_NULLABLE
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'users' 
AND COLUMN_NAME = 'stripe_customer_id';

-- Se não existir, adicionar
-- ALTER TABLE users ADD COLUMN stripe_customer_id VARCHAR(255) NULL AFTER email;

-- ===================================
-- FIM DA VERIFICAÇÃO
-- ===================================
