# 🎯 SISTEMA DE TRIAL (PERÍODO DE TESTE)

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 08/11/2025  
**Projeto:** ConectCorretores  
**Versão:** 1.6.0

---

## 📋 VISÃO GERAL

O sistema de trial permite que novos usuários testem o ConectCorretores gratuitamente por 7 dias antes de assinar um plano pago.

### **Características:**
- ✅ 7 dias de teste gratuito
- ✅ Acesso completo às funcionalidades do plano
- ✅ Sem necessidade de cartão de crédito
- ✅ Conversão automática para plano pago (opcional)
- ✅ Emails automáticos de notificação
- ✅ Estatísticas de conversão

---

## 🗄️ ESTRUTURA DO BANCO DE DADOS

### **Novos Campos na Tabela `subscriptions`:**

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `is_trial` | TINYINT(1) | Se é período de teste (0=não, 1=sim) |
| `trial_ends_at` | TIMESTAMP | Data/hora de término do trial |
| `converted_from_trial` | TINYINT(1) | Se foi convertido de trial (0=não, 1=sim) |
| `converted_at` | TIMESTAMP | Data/hora da conversão |

### **Novo Status:**
- `trial` - Em período de teste

### **Migration:**
```sql
-- Executar: database/migrations/migration_20251108_add_trial_fields.sql
```

---

## 🔄 FLUXO DO TRIAL

### **1. Usuário Inicia Trial**
```
Usuário → Planos → Iniciar Trial → Trial Criado → Email Boas-Vindas
```

**URL:** `/planos/iniciar_trial/{plan_id}`

**Validações:**
- ✅ Usuário deve estar logado
- ✅ Plano deve existir e estar ativo
- ✅ Usuário não pode ter assinatura ativa
- ✅ Usuário não pode ter usado trial antes

### **2. Durante o Trial (7 dias)**
```
Dia 1-4: Usuário usa o sistema normalmente
Dia 5: Email "Trial expira em 3 dias"
Dia 6: Email "Trial expira em 1 dia"
Dia 7: Trial expira
```

### **3. Trial Expira**
```
Cron Job → Expira Trial → Email "Trial Expirado" → Acesso Bloqueado
```

### **4. Conversão para Pago (Opcional)**
```
Usuário → Checkout → Pagamento → Trial Convertido → Email "Assinatura Ativada"
```

---

## 📧 EMAILS AUTOMÁTICOS

### **1. Boas-Vindas ao Trial**
- **Quando:** Ao iniciar o trial
- **Template:** `trial_welcome.php`
- **Assunto:** "Bem-vindo ao seu período de teste gratuito! 🎉"

### **2. Trial Expirando (3 dias)**
- **Quando:** 3 dias antes de expirar
- **Template:** `trial_expiring.php`
- **Assunto:** "Seu período de teste termina em 3 dias ⏰"

### **3. Trial Expirando (1 dia)**
- **Quando:** 1 dia antes de expirar
- **Template:** `trial_expiring.php`
- **Assunto:** "Seu período de teste termina em 1 dia ⏰"

### **4. Trial Expirado**
- **Quando:** Quando o trial expira
- **Template:** `trial_expired.php`
- **Assunto:** "Seu período de teste expirou 😢"

### **5. Trial Convertido**
- **Quando:** Ao converter para plano pago
- **Template:** `trial_converted.php`
- **Assunto:** "Assinatura ativada com sucesso! 🎉"

---

## 🔧 MÉTODOS DISPONÍVEIS

### **Subscription_model:**

```php
// Criar trial
$subscription_id = $this->Subscription_model->create_trial($user_id, $plan_id, $trial_days = 7);

// Verificar se usuário já usou trial
$has_used = $this->Subscription_model->has_used_trial($user_id);

// Buscar trial ativo
$trial = $this->Subscription_model->get_active_trial($user_id);

// Converter trial para pago
$this->Subscription_model->convert_trial_to_paid($subscription_id, $stripe_sub_id, $stripe_customer_id);

// Buscar trials expirando
$trials = $this->Subscription_model->get_trials_expiring_soon($days = 3);

// Expirar trials vencidos
$count = $this->Subscription_model->expire_trials();

// Estatísticas
$active = $this->Subscription_model->count_active_trials();
$conversions = $this->Subscription_model->count_trial_conversions();
$rate = $this->Subscription_model->get_trial_conversion_rate();
```

### **Email_lib:**

```php
// Email de boas-vindas
$this->email_lib->send_trial_welcome($user, $subscription);

// Email de trial expirando
$this->email_lib->send_trial_expiring($user, $subscription, $days_left);

// Email de trial expirado
$this->email_lib->send_trial_expired($user, $subscription);

// Email de conversão
$this->email_lib->send_trial_converted($user, $subscription);
```

### **Controller Planos:**

```php
// Iniciar trial
/planos/iniciar_trial/{plan_id}
```

---

## ⏰ CRON JOBS

### **1. Processar Trials Expirados** (OBRIGATÓRIO)
```bash
# Diariamente às 2h
0 2 * * * wget -q -O - "https://conectcorretores.doisr.com.br/cron/process_expired_trials?token=TOKEN" >/dev/null 2>&1
```

### **2. Enviar Lembretes** (OBRIGATÓRIO)
```bash
# Diariamente às 10h
0 10 * * * wget -q -O - "https://conectcorretores.doisr.com.br/cron/send_trial_reminders?token=TOKEN" >/dev/null 2>&1
```

### **3. Estatísticas** (OPCIONAL)
```bash
# Semanalmente (segunda às 9h)
0 9 * * 1 wget -q -O - "https://conectcorretores.doisr.com.br/cron/trial_stats?token=TOKEN" > /logs/trial_stats.log 2>&1
```

**Documentação completa:** `docs/desenvolvimento/CRON-TRIAL.md`

---

## 🎨 INTERFACE DO USUÁRIO

### **Página de Planos:**

Adicionar botão "Testar Grátis" nos cards de planos:

```php
<?php if (!$this->Subscription_model->has_used_trial($user_id)): ?>
    <a href="<?php echo base_url('planos/iniciar_trial/' . $plan->id); ?>" 
       class="btn btn-success">
        🎁 Testar Grátis por 7 Dias
    </a>
<?php else: ?>
    <a href="<?php echo base_url('planos/escolher/' . $plan->id); ?>" 
       class="btn btn-primary">
        Assinar Agora
    </a>
<?php endif; ?>
```

### **Dashboard (Trial Ativo):**

Mostrar banner com dias restantes:

```php
<?php if ($subscription->is_trial): ?>
    <?php $days_left = ceil((strtotime($subscription->trial_ends_at) - time()) / 86400); ?>
    <div class="alert alert-warning">
        ⏰ Seu período de teste expira em <strong><?php echo $days_left; ?> dias</strong>
        (<php echo date('d/m/Y', strtotime($subscription->trial_ends_at)); ?>).
        <a href="<?php echo base_url('planos'); ?>">Assinar agora</a> para continuar usando.
    </div>
<?php endif; ?>
```

---

## 📊 ESTATÍSTICAS E MÉTRICAS

### **Métricas Importantes:**

1. **Taxa de Conversão:**
   - Trials convertidos / Total de trials
   - Meta: > 30%

2. **Tempo Médio de Conversão:**
   - Quantos dias do trial até converter
   - Meta: < 5 dias

3. **Trials Ativos:**
   - Quantos trials estão ativos agora
   - Monitorar crescimento

4. **Trials Expirados sem Conversão:**
   - Oportunidades perdidas
   - Analisar motivos

### **Consultas SQL Úteis:**

```sql
-- Taxa de conversão
SELECT 
    COUNT(*) as total_trials,
    SUM(converted_from_trial) as conversions,
    ROUND((SUM(converted_from_trial) / COUNT(*)) * 100, 2) as conversion_rate
FROM subscriptions 
WHERE is_trial = 1;

-- Trials ativos
SELECT COUNT(*) 
FROM subscriptions 
WHERE status = 'trial' 
AND trial_ends_at >= NOW();

-- Trials expirando hoje
SELECT u.nome, u.email, s.trial_ends_at
FROM subscriptions s
JOIN users u ON u.id = s.user_id
WHERE s.status = 'trial'
AND DATE(s.trial_ends_at) = CURDATE();

-- Conversões por plano
SELECT 
    p.nome as plano,
    COUNT(*) as conversions
FROM subscriptions s
JOIN plans p ON p.id = s.plan_id
WHERE s.converted_from_trial = 1
GROUP BY p.id
ORDER BY conversions DESC;
```

---

## 🚀 DEPLOY

### **1. Executar Migration:**

```bash
# Via SSH
mysql -u user -p database < database/migrations/migration_20251108_add_trial_fields.sql

# Ou via phpMyAdmin
# Copiar e executar o conteúdo do arquivo
```

### **2. Configurar Cron Jobs:**

Ver: `docs/desenvolvimento/CRON-TRIAL.md`

### **3. Testar:**

```bash
# 1. Criar trial
curl "https://conectcorretores.doisr.com.br/planos/iniciar_trial/1"

# 2. Verificar emails
# Acessar: https://app.brevo.com/

# 3. Testar cron jobs
curl "https://conectcorretores.doisr.com.br/cron/trial_stats?token=TOKEN"
```

---

## 🔒 SEGURANÇA

### **Validações Implementadas:**

1. ✅ Usuário só pode ter 1 trial por conta
2. ✅ Trial só pode ser criado se não houver assinatura ativa
3. ✅ Cron jobs protegidos por token
4. ✅ Emails enviados apenas para usuários válidos

### **Prevenção de Abuso:**

- Verificar email duplicado no cadastro
- Limitar trials por IP (futuro)
- Verificar cartão de crédito (futuro)

---

## 📝 CHECKLIST DE IMPLEMENTAÇÃO

- [x] Migration criada
- [x] Model atualizado (14 métodos)
- [x] Email_lib atualizado (4 métodos)
- [x] Templates de email criados (4)
- [x] Cron jobs implementados (3)
- [x] Controller atualizado
- [x] Documentação criada
- [ ] Migration executada no banco
- [ ] Cron jobs configurados no cPanel
- [ ] Interface atualizada (views)
- [ ] Testes realizados
- [ ] Deploy em produção

---

## 🎯 PRÓXIMOS PASSOS

1. **Executar migration no banco de dados**
2. **Configurar cron jobs no cPanel**
3. **Atualizar views de planos** (adicionar botão "Testar Grátis")
4. **Atualizar dashboard** (mostrar status do trial)
5. **Testar fluxo completo**
6. **Monitorar conversões**

---

## 📞 SUPORTE

**Desenvolvedor:** Rafael Dias  
**Site:** doisr.com.br  
**Email:** doisr.sistemas@gmail.com

---

**Última atualização:** 08/11/2025
