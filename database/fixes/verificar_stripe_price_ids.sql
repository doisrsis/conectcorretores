-- ========================================
-- Verificar Stripe Price IDs nos Planos
-- Autor: Rafael Dias - doisr.com.br
-- Data: 06/11/2025
-- ========================================

-- Verificar planos atuais
SELECT 
    id,
    nome,
    tipo,
    preco,
    stripe_price_id,
    ativo
FROM plans
ORDER BY preco ASC;

-- ========================================
-- INSTRUÇÕES:
-- ========================================
-- 
-- 1. Execute este script para ver os planos atuais
-- 
-- 2. Acesse o Stripe Dashboard:
--    https://dashboard.stripe.com/test/products
-- 
-- 3. Para cada plano, copie o Price ID correto
-- 
-- 4. Execute os UPDATEs abaixo com os IDs corretos:
-- 
-- UPDATE plans SET stripe_price_id = 'price_xxxxx' WHERE id = 1;
-- UPDATE plans SET stripe_price_id = 'price_xxxxx' WHERE id = 2;
-- UPDATE plans SET stripe_price_id = 'price_xxxxx' WHERE id = 3;
-- 
-- ========================================
