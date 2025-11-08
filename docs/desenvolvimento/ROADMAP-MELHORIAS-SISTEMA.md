# 🚀 Roadmap: Melhorias do Sistema de Assinaturas

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 08/11/2025  
**Status:** 📋 Planejamento  
**Versão Alvo:** v1.5.0

---

## 📋 Sumário das Melhorias

1. **Período de Teste Gratuito (Free Trial)**
2. **Painel de Configurações Admin**
3. **Sistema de Cupons de Desconto**

---

## 🔍 Análise do Código Atual

### ✅ **O Que Já Temos:**

#### **Estrutura de Banco de Dados:**
```sql
✅ Tabela: plans
   - Campos básicos de plano
   - Integração com Stripe (stripe_price_id, stripe_product_id)
   - Limite de imóveis
   
✅ Tabela: subscriptions
   - Status: 'ativa', 'cancelada', 'pendente', 'expirada'
   - Datas de início e fim
   - Integração com Stripe
   
❌ NÃO EXISTE: Tabela de configurações
❌ NÃO EXISTE: Tabela de cupons
❌ NÃO EXISTE: Campo trial_days em plans
```

#### **Models Existentes:**
```
✅ Plan_model.php - Gerenciamento de planos
✅ Subscription_model.php - Gerenciamento de assinaturas
✅ User_model.php - Gerenciamento de usuários
❌ NÃO EXISTE: Settings_model.php
❌ NÃO EXISTE: Coupon_model.php
```

#### **Controllers Existentes:**
```
✅ Admin.php - Painel administrativo
✅ Planos.php - Página de planos e checkout
✅ Dashboard.php - Dashboard do cliente
❌ NÃO EXISTE: Settings.php (admin)
❌ NÃO EXISTE: Coupons.php (admin)
```

#### **Bibliotecas Stripe:**
```
✅ stripe-php/lib/Coupon.php - Suporte a cupons
✅ stripe-php/lib/PromotionCode.php - Códigos promocionais
✅ stripe-php/lib/Subscription.php - trial_end, trial_period_days
```

### 🎯 **Conclusão:**
- ✅ **Stripe já suporta:** Trial periods e cupons nativamente
- ✅ **Código atual:** Bem estruturado e organizado
- ❌ **Falta implementar:** Camada de aplicação para usar recursos do Stripe
- ❌ **Falta criar:** Sistema de configurações centralizadas

---

## 📦 MELHORIA 1: Período de Teste Gratuito

### **Objetivo:**
Permitir que novos usuários testem o sistema gratuitamente por X dias antes da primeira cobrança.

### **Impacto no Projeto:**

#### **🟢 BAIXO IMPACTO - Aproveitando Stripe**

O Stripe já possui suporte nativo a trial periods. Precisamos apenas:
1. Adicionar campo `trial_days` na tabela `plans`
2. Modificar checkout para incluir trial period
3. Atualizar UI para mostrar "X dias grátis"
4. Adicionar lógica para detectar período de teste

### **Arquivos a Modificar:**

```
📝 BANCO DE DADOS:
   ✏️ database/migrations/migration_20251108_add_trial_days.sql
   
📝 MODELS:
   ✏️ application/models/Plan_model.php
   
📝 CONTROLLERS:
   ✏️ application/controllers/Planos.php (checkout)
   ✏️ application/controllers/Admin.php (gerenciar planos)
   
📝 LIBRARIES:
   ✏️ application/libraries/Stripe_lib.php (create_subscription com trial)
   
📝 VIEWS:
   ✏️ application/views/planos/index.php (badge "X dias grátis")
   ✏️ application/views/admin/planos.php (campo trial_days)
   ✏️ application/views/dashboard/index.php (mostrar "em teste")
   
📝 HELPERS:
   ✏️ application/helpers/subscription_helper.php (detectar trial)
```

### **Fluxo de Implementação:**

```
1. Adicionar campo trial_days em plans
   ↓
2. Modificar Plan_model para incluir trial_days
   ↓
3. Atualizar Stripe_lib->create_subscription() para enviar trial_period_days
   ↓
4. Modificar checkout para aplicar trial
   ↓
5. Atualizar UI para mostrar badge "X dias grátis"
   ↓
6. Adicionar helper para detectar se está em trial
   ↓
7. Mostrar status "Em Teste" no dashboard
```

### **Estimativa:**
- ⏱️ **Tempo:** 3-4 horas
- 🔧 **Complexidade:** Baixa
- 🎯 **Prioridade:** Alta

---

## 📦 MELHORIA 2: Painel de Configurações Admin

### **Objetivo:**
Centralizar configurações do sistema em um painel admin, permitindo ajustar:
- Dias de período de teste padrão
- Dias de período de graça (falha de pagamento)
- Outras configurações futuras

### **Impacto no Projeto:**

#### **🟡 MÉDIO IMPACTO - Nova Estrutura**

Precisamos criar uma nova tabela e sistema de configurações:
1. Criar tabela `settings`
2. Criar model `Settings_model`
3. Criar controller `Settings` (admin)
4. Criar views de configuração
5. Migrar valores hardcoded para configurações

### **Arquivos a Criar:**

```
📝 BANCO DE DADOS:
   ✨ database/migrations/migration_20251108_create_settings.sql
   
📝 MODELS:
   ✨ application/models/Settings_model.php
   
📝 CONTROLLERS:
   ✨ application/controllers/Settings.php (admin)
   
📝 VIEWS:
   ✨ application/views/admin/settings/index.php
   ✨ application/views/admin/settings/assinaturas.php
   ✨ application/views/admin/settings/geral.php
   
📝 HELPERS:
   ✨ application/helpers/settings_helper.php
```

### **Estrutura da Tabela Settings:**

```sql
CREATE TABLE `settings` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `chave` varchar(100) NOT NULL UNIQUE,
  `valor` text NOT NULL,
  `tipo` enum('string','int','bool','json') DEFAULT 'string',
  `grupo` varchar(50) DEFAULT 'geral',
  `descricao` text,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chave` (`chave`),
  KEY `grupo` (`grupo`)
);
```

### **Configurações Iniciais:**

```php
// Assinaturas
'trial_days_default' => 7
'grace_period_days' => 14
'max_retry_attempts' => 4
'retry_interval_days' => 3

// Sistema
'site_name' => 'ConectCorretores'
'site_email' => 'contato@conectcorretores.com.br'
'maintenance_mode' => false

// Notificações
'email_payment_failed' => true
'email_subscription_created' => true
'email_subscription_canceled' => true
```

### **Fluxo de Implementação:**

```
1. Criar tabela settings
   ↓
2. Criar Settings_model com cache
   ↓
3. Criar settings_helper para acesso fácil
   ↓
4. Criar controller Settings (admin)
   ↓
5. Criar views de configuração
   ↓
6. Migrar valores hardcoded:
   - 14 dias de graça → get_setting('grace_period_days')
   - 4 tentativas → get_setting('max_retry_attempts')
   ↓
7. Adicionar menu no admin
   ↓
8. Criar seeder com valores padrão
```

### **Estimativa:**
- ⏱️ **Tempo:** 5-6 horas
- 🔧 **Complexidade:** Média
- 🎯 **Prioridade:** Alta

---

## 📦 MELHORIA 3: Sistema de Cupons de Desconto

### **Objetivo:**
Permitir criar cupons de desconto para assinaturas (% ou valor fixo).

### **Impacto no Projeto:**

#### **🟡 MÉDIO IMPACTO - Aproveitando Stripe**

O Stripe já possui sistema completo de cupons. Precisamos:
1. Criar tabela local para sincronizar cupons
2. Criar interface admin para gerenciar cupons
3. Adicionar campo no checkout para aplicar cupom
4. Validar e aplicar cupom via Stripe

### **Arquivos a Criar:**

```
📝 BANCO DE DADOS:
   ✨ database/migrations/migration_20251108_create_coupons.sql
   
📝 MODELS:
   ✨ application/models/Coupon_model.php
   
📝 CONTROLLERS:
   ✨ application/controllers/Coupons.php (admin)
   ✏️ application/controllers/Planos.php (aplicar cupom)
   
📝 LIBRARIES:
   ✏️ application/libraries/Stripe_lib.php (coupon methods)
   
📝 VIEWS:
   ✨ application/views/admin/coupons/index.php
   ✨ application/views/admin/coupons/create.php
   ✨ application/views/admin/coupons/edit.php
   ✏️ application/views/planos/checkout.php (campo cupom)
```

### **Estrutura da Tabela Coupons:**

```sql
CREATE TABLE `coupons` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL UNIQUE,
  `stripe_coupon_id` varchar(255) DEFAULT NULL,
  `tipo` enum('percent','fixed') NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `duracao` enum('once','repeating','forever') DEFAULT 'once',
  `duracao_meses` int(11) DEFAULT NULL,
  `max_usos` int(11) DEFAULT NULL,
  `usos_atuais` int(11) DEFAULT 0,
  `valido_ate` date DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
);
```

### **Funcionalidades:**

```
✅ Criar cupom no admin
✅ Sincronizar com Stripe
✅ Validar cupom no checkout
✅ Aplicar desconto na assinatura
✅ Rastrear uso de cupons
✅ Desativar cupom automaticamente (max_usos ou data)
✅ Relatório de cupons usados
```

### **Fluxo de Implementação:**

```
1. Criar tabela coupons
   ↓
2. Criar Coupon_model
   ↓
3. Adicionar métodos no Stripe_lib:
   - create_coupon()
   - validate_coupon()
   - apply_coupon_to_subscription()
   ↓
4. Criar controller Coupons (admin)
   ↓
5. Criar views de gerenciamento
   ↓
6. Adicionar campo no checkout
   ↓
7. Validar e aplicar cupom
   ↓
8. Registrar uso do cupom
   ↓
9. Mostrar desconto aplicado
```

### **Estimativa:**
- ⏱️ **Tempo:** 6-8 horas
- 🔧 **Complexidade:** Média
- 🎯 **Prioridade:** Média

---

## 📊 Resumo de Impacto

### **Banco de Dados:**
```
✨ 3 novas tabelas:
   - settings (configurações)
   - coupons (cupons)
   
✏️ 1 tabela modificada:
   - plans (adicionar trial_days)
```

### **Models:**
```
✨ 2 novos models:
   - Settings_model.php
   - Coupon_model.php
   
✏️ 1 model modificado:
   - Plan_model.php
```

### **Controllers:**
```
✨ 2 novos controllers:
   - Settings.php (admin)
   - Coupons.php (admin)
   
✏️ 2 controllers modificados:
   - Planos.php (trial + cupons)
   - Admin.php (links para novos recursos)
```

### **Views:**
```
✨ 8 novas views:
   - admin/settings/* (3 views)
   - admin/coupons/* (3 views)
   - Modificações em planos e checkout (2 views)
```

### **Helpers:**
```
✨ 2 novos helpers:
   - settings_helper.php
   
✏️ 1 helper modificado:
   - subscription_helper.php (detectar trial)
```

---

## 🎯 Ordem de Implementação Sugerida

### **FASE 1: Configurações (Base)** ⏱️ 5-6h
```
1. Criar sistema de configurações
2. Migrar valores hardcoded
3. Testar configurações
```

### **FASE 2: Trial Period** ⏱️ 3-4h
```
1. Adicionar trial_days em plans
2. Implementar trial no checkout
3. Atualizar UI
4. Testar período de teste
```

### **FASE 3: Sistema de Cupons** ⏱️ 6-8h
```
1. Criar estrutura de cupons
2. Integrar com Stripe
3. Interface admin
4. Aplicação no checkout
5. Testes completos
```

---

## ⏱️ Estimativa Total

```
📊 TEMPO TOTAL: 14-18 horas
🔧 COMPLEXIDADE: Média
📦 VERSÃO: v1.5.0 (MINOR - Novas funcionalidades)
```

---

## ✅ Checklist de Implementação

### **Antes de Começar:**
- [ ] Backup do banco de dados
- [ ] Criar branch: `feature/subscription-improvements`
- [ ] Documentar estado atual

### **Durante Desenvolvimento:**
- [ ] Seguir padrão de código existente
- [ ] Comentar código complexo
- [ ] Criar migrations versionadas
- [ ] Testar cada funcionalidade isoladamente
- [ ] Documentar APIs criadas

### **Após Implementação:**
- [ ] Testes de integração
- [ ] Documentação de usuário
- [ ] Atualizar README
- [ ] Criar tag v1.5.0
- [ ] Deploy em staging primeiro

---

## 🎨 Mockups de Interface

### **Configurações Admin:**
```
┌─────────────────────────────────────┐
│ ⚙️ Configurações do Sistema         │
├─────────────────────────────────────┤
│                                     │
│ 📋 Assinaturas                      │
│ ├─ Período de Teste: [7] dias      │
│ ├─ Período de Graça: [14] dias     │
│ ├─ Tentativas de Cobrança: [4]     │
│ └─ Intervalo entre Tentativas: [3] │
│                                     │
│ 📧 Notificações                     │
│ ├─ ☑ Email falha de pagamento      │
│ ├─ ☑ Email nova assinatura         │
│ └─ ☑ Email cancelamento            │
│                                     │
│ [Salvar Configurações]              │
└─────────────────────────────────────┘
```

### **Gerenciar Cupons:**
```
┌─────────────────────────────────────────────┐
│ 🎟️ Cupons de Desconto                      │
├─────────────────────────────────────────────┤
│ [+ Novo Cupom]                              │
│                                             │
│ Código    Tipo      Valor   Usos   Status  │
│ ─────────────────────────────────────────── │
│ BEMVINDO  Percent   20%     5/10   ✅ Ativo│
│ PROMO50   Fixed     R$50    ∞      ✅ Ativo│
│ BLACK2024 Percent   50%     0/100  ❌ Expirado│
└─────────────────────────────────────────────┘
```

### **Checkout com Cupom:**
```
┌─────────────────────────────────────┐
│ 💳 Finalizar Assinatura             │
├─────────────────────────────────────┤
│ Plano: Plano II                     │
│ Valor: R$ 39,90/mês                 │
│                                     │
│ 🎟️ Tem um cupom?                   │
│ [_______________] [Aplicar]         │
│                                     │
│ ✅ Cupom BEMVINDO aplicado!         │
│ Desconto: -R$ 7,98 (20%)            │
│                                     │
│ Total: R$ 31,92/mês                 │
│                                     │
│ 🎁 7 dias grátis para testar!       │
│ Primeira cobrança: 15/11/2025       │
│                                     │
│ [Confirmar Assinatura]              │
└─────────────────────────────────────┘
```

---

## 🚨 Pontos de Atenção

### **1. Período de Teste:**
- ⚠️ Não cobrar durante trial
- ⚠️ Cancelar antes do fim do trial = sem cobrança
- ⚠️ Mostrar claramente quando será cobrado
- ⚠️ Email de lembrete antes da primeira cobrança

### **2. Configurações:**
- ⚠️ Validar valores (não permitir negativos)
- ⚠️ Cache de configurações (performance)
- ⚠️ Log de alterações (auditoria)
- ⚠️ Valores padrão se não configurado

### **3. Cupons:**
- ⚠️ Validar antes de aplicar
- ⚠️ Não permitir múltiplos cupons
- ⚠️ Sincronizar com Stripe
- ⚠️ Rastrear uso corretamente
- ⚠️ Desativar automaticamente quando expirar

---

## 📚 Referências

- [Stripe Trials Documentation](https://stripe.com/docs/billing/subscriptions/trials)
- [Stripe Coupons Documentation](https://stripe.com/docs/billing/subscriptions/coupons)
- [Stripe Promotion Codes](https://stripe.com/docs/billing/subscriptions/discounts/codes)

---

**Pronto para começar? Qual fase você quer implementar primeiro? 🚀**
