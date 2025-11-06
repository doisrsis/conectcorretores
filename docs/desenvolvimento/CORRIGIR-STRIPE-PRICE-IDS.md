# 🔧 Corrigir Stripe Price IDs

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 06/11/2025

---

## 🐛 Problema

Erro ao tentar assinar plano:
```
Erro: No such price: 'price_1SJDtI13H0xINMprmrUKWfem'
```

**Causa:** O `stripe_price_id` no banco de dados não existe no Stripe.

---

## ✅ Solução

### **Passo 1: Verificar Planos no Banco**

Execute no phpMyAdmin ou MySQL:

```sql
SELECT id, nome, preco, stripe_price_id 
FROM plans 
ORDER BY preco ASC;
```

Você verá algo como:
```
+----+----------------+-------+--------------------------------+
| id | nome           | preco | stripe_price_id                |
+----+----------------+-------+--------------------------------+
|  1 | Básico         | 29.90 | price_1SJDtI13H0xINMprmrUKWfem|
|  2 | Profissional   | 49.90 | price_xxxxx                    |
|  3 | Premium        | 99.90 | price_xxxxx                    |
+----+----------------+-------+--------------------------------+
```

---

### **Passo 2: Criar Produtos e Preços no Stripe**

#### **Opção A: Usar Produtos Existentes**

1. **Acessar Dashboard:**
   ```
   https://dashboard.stripe.com/test/products
   ```

2. **Para cada produto, copiar o Price ID:**
   - Clicar no produto
   - Na seção "Pricing", copiar o ID (ex: `price_xxxxx`)

#### **Opção B: Criar Novos Produtos**

1. **Acessar:** https://dashboard.stripe.com/test/products

2. **Criar Produto "Básico":**
   - Nome: `ConectCorretores - Básico`
   - Descrição: `Plano Básico - Até 5 imóveis`
   - Preço: `R$ 29,90 / mês`
   - Tipo: `Recurring` (Recorrente)
   - Intervalo: `Monthly` (Mensal)
   - Copiar o **Price ID** gerado

3. **Criar Produto "Profissional":**
   - Nome: `ConectCorretores - Profissional`
   - Descrição: `Plano Profissional - Até 15 imóveis`
   - Preço: `R$ 49,90 / mês`
   - Tipo: `Recurring`
   - Intervalo: `Monthly`
   - Copiar o **Price ID**

4. **Criar Produto "Premium":**
   - Nome: `ConectCorretores - Premium`
   - Descrição: `Plano Premium - Imóveis ilimitados`
   - Preço: `R$ 99,90 / mês`
   - Tipo: `Recurring`
   - Intervalo: `Monthly`
   - Copiar o **Price ID**

---

### **Passo 3: Atualizar Banco de Dados**

Execute no phpMyAdmin ou MySQL:

```sql
-- Atualizar Plano Básico
UPDATE plans 
SET stripe_price_id = 'price_XXXXX_BASICO' 
WHERE id = 1;

-- Atualizar Plano Profissional
UPDATE plans 
SET stripe_price_id = 'price_XXXXX_PROFISSIONAL' 
WHERE id = 2;

-- Atualizar Plano Premium
UPDATE plans 
SET stripe_price_id = 'price_XXXXX_PREMIUM' 
WHERE id = 3;
```

**⚠️ IMPORTANTE:** Substitua `price_XXXXX` pelos IDs reais copiados do Stripe!

---

### **Passo 4: Verificar Atualização**

```sql
SELECT id, nome, preco, stripe_price_id 
FROM plans 
ORDER BY preco ASC;
```

Todos os planos devem ter `stripe_price_id` válidos.

---

### **Passo 5: Testar Assinatura**

1. **Acessar:** http://localhost/conectcorretores/planos
2. **Clicar em:** "Assinar Agora"
3. **Verificar:** Deve redirecionar para checkout do Stripe
4. **Usar cartão de teste:**
   ```
   Número: 4242 4242 4242 4242
   Data: Qualquer data futura
   CVC: Qualquer 3 dígitos
   ```

---

## 🎯 Exemplo Completo

### **Produtos Criados no Stripe:**

```
Produto 1: ConectCorretores - Básico
├─ Price ID: price_1QJxxx000000001
├─ Valor: R$ 29,90/mês
└─ Descrição: Até 5 imóveis

Produto 2: ConectCorretores - Profissional
├─ Price ID: price_1QJxxx000000002
├─ Valor: R$ 49,90/mês
└─ Descrição: Até 15 imóveis

Produto 3: ConectCorretores - Premium
├─ Price ID: price_1QJxxx000000003
├─ Valor: R$ 99,90/mês
└─ Descrição: Imóveis ilimitados
```

### **SQL de Atualização:**

```sql
UPDATE plans SET stripe_price_id = 'price_1QJxxx000000001' WHERE id = 1;
UPDATE plans SET stripe_price_id = 'price_1QJxxx000000002' WHERE id = 2;
UPDATE plans SET stripe_price_id = 'price_1QJxxx000000003' WHERE id = 3;
```

---

## 🔍 Verificação Final

### **Checklist:**
- [ ] Produtos criados no Stripe
- [ ] Price IDs copiados
- [ ] Banco de dados atualizado
- [ ] Teste de assinatura funcionando
- [ ] Checkout abre corretamente
- [ ] Webhook recebe eventos

---

## 🐛 Troubleshooting

### **Erro: "No such price"**
**Causa:** Price ID incorreto  
**Solução:** Verificar se copiou o ID correto do Stripe

### **Erro: "Invalid currency"**
**Causa:** Moeda configurada errada  
**Solução:** Criar preços em BRL no Stripe

### **Checkout não abre**
**Causa:** Chave pública do Stripe incorreta  
**Solução:** Verificar `stripe_test_public_key` em `config/stripe.php`

### **Webhook não funciona**
**Causa:** Webhook secret incorreto  
**Solução:** Verificar `stripe_webhook_secret_test` em `config/stripe.php`

---

## 📚 Referências

- [Stripe Products Dashboard](https://dashboard.stripe.com/test/products)
- [Stripe Prices API](https://stripe.com/docs/api/prices)
- [Criar Produtos no Stripe](https://stripe.com/docs/products-prices/getting-started)

---

## 💡 Dica

Para facilitar, você pode criar um script que sincroniza os planos do banco com o Stripe automaticamente. Isso evita erros manuais.

---

**Price IDs corretos = Checkout funcionando! 💳**

Para suporte: Rafael Dias - doisr.com.br
