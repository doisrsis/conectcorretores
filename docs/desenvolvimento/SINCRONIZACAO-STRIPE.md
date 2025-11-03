# 🔄 Sistema de Sincronização com Stripe

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 19/10/2025  
**Versão:** 1.0

---

## 📋 Visão Geral

Sistema completo de sincronização entre o banco de dados local e o Stripe, garantindo que os dados de assinaturas estejam sempre atualizados através de 3 estratégias complementares:

1. **Webhook** (Tempo Real) - Stripe notifica mudanças instantaneamente
2. **Sincronização no Login** (Backup) - Atualiza quando usuário acessa o dashboard
3. **Cron Job** (Limpeza) - Sincronização diária de todas as assinaturas

---

## 🎯 Problema Resolvido

### **Antes:**
❌ Dados apenas no banco de dados local  
❌ Dessincronia se usuário cancelar no Stripe  
❌ Falhas de pagamento não detectadas  
❌ Status desatualizado entre sistemas  

### **Depois:**
✅ Sincronização em tempo real via webhook  
✅ Backup via sincronização no login  
✅ Limpeza diária via cron job  
✅ Dados sempre consistentes  
✅ Detecção automática de mudanças  

---

## 🔧 Implementação

### **1. Sincronização no Login (Dashboard)**

#### **Arquivo:** `application/controllers/Dashboard.php`

**Quando acontece:**
- Toda vez que usuário acessa `/dashboard`
- Toda vez que usuário acessa `/perfil`

**O que faz:**
1. Busca assinatura no banco de dados
2. Se existir, consulta no Stripe
3. Compara dados (status, data_fim, plan_id)
4. Atualiza banco se houver diferenças
5. Registra logs das mudanças

**Código:**
```php
// No método index()
if ($data['subscription']) {
    $this->_sync_subscription_with_stripe($data['subscription']);
    $data['subscription'] = $this->Subscription_model->get_active_by_user($user_id);
}
```

**Método privado:**
```php
private function _sync_subscription_with_stripe($local_subscription) {
    // 1. Buscar no Stripe
    $stripe_result = $this->stripe_lib->retrieve_subscription(
        $local_subscription->stripe_subscription_id
    );
    
    // 2. Comparar dados
    $update_data = [];
    
    // Status
    if ($stripe_status !== $local_subscription->status) {
        $update_data['status'] = $stripe_status;
    }
    
    // Data de fim
    if ($stripe_end_date !== $local_subscription->data_fim) {
        $update_data['data_fim'] = $stripe_end_date;
    }
    
    // Plano
    if ($stripe_price_id !== $local_subscription->stripe_price_id) {
        $plan = $this->Plan_model->get_by_stripe_price_id($stripe_price_id);
        $update_data['plan_id'] = $plan->id;
    }
    
    // 3. Atualizar se necessário
    if (!empty($update_data)) {
        $this->Subscription_model->update($local_subscription->id, $update_data);
    }
}
```

---

### **2. Webhook do Stripe (Tempo Real)**

#### **Arquivo:** `application/controllers/Planos.php`

**Quando acontece:**
- Stripe envia notificação quando algo muda
- Eventos: criação, atualização, cancelamento, pagamento

**Eventos Monitorados:**
```php
switch ($event->type) {
    case 'checkout.session.completed':
        // Nova assinatura criada
        break;
        
    case 'customer.subscription.updated':
        // Assinatura atualizada (upgrade/downgrade/mudança)
        break;
        
    case 'customer.subscription.deleted':
        // Assinatura cancelada
        break;
        
    case 'invoice.payment_succeeded':
        // Pagamento bem-sucedido (renovação)
        break;
        
    case 'invoice.payment_failed':
        // Pagamento falhou
        break;
}
```

**Novo Método Adicionado:**
```php
private function _handle_subscription_updated($stripe_subscription) {
    $subscription = $this->Subscription_model->get_by_stripe_id(
        $stripe_subscription->id
    );
    
    if ($subscription) {
        $update_data = [];
        
        // Atualizar status
        $update_data['status'] = $this->_map_stripe_status(
            $stripe_subscription->status
        );
        
        // Atualizar data de fim
        $update_data['data_fim'] = date('Y-m-d', 
            $stripe_subscription->current_period_end
        );
        
        // Atualizar plano
        $stripe_price_id = $stripe_subscription->items->data[0]->price->id;
        $plan = $this->Plan_model->get_by_stripe_price_id($stripe_price_id);
        if ($plan) {
            $update_data['plan_id'] = $plan->id;
        }
        
        $this->Subscription_model->update($subscription->id, $update_data);
    }
}
```

**Configuração do Webhook:**
1. Acessar: https://dashboard.stripe.com/webhooks
2. Adicionar endpoint: `https://seudominio.com/planos/webhook`
3. Selecionar eventos:
   - `checkout.session.completed`
   - `customer.subscription.updated`
   - `customer.subscription.deleted`
   - `invoice.payment_succeeded`
   - `invoice.payment_failed`
4. Copiar "Signing secret" (whsec_...)
5. Adicionar em `application/config/stripe.php`:
   ```php
   $config['stripe_webhook_secret'] = 'whsec_...';
   ```

---

### **3. Cron Job (Sincronização Diária)**

#### **Arquivo:** `application/controllers/Cron.php`

**Quando acontece:**
- Executado automaticamente todos os dias (ex: 3h da manhã)
- Ou manualmente via URL

**Métodos:**

#### **A. `sync_subscriptions()` - Sincronizar Todas**
```php
public function sync_subscriptions() {
    // Buscar todas as assinaturas ativas/pendentes
    $subscriptions = $this->Subscription_model->get_all([
        'status' => ['ativa', 'pendente', 'trial']
    ]);
    
    foreach ($subscriptions as $subscription) {
        // Buscar no Stripe
        $stripe_sub = $this->stripe_lib->retrieve_subscription(
            $subscription->stripe_subscription_id
        );
        
        // Comparar e atualizar
        // ...
        
        // Delay para não sobrecarregar API
        sleep(1);
    }
}
```

#### **B. `check_expired()` - Verificar Expiradas**
```php
public function check_expired() {
    // Buscar assinaturas ativas que já expiraram
    $this->db->where('status', 'ativa');
    $this->db->where('data_fim <', date('Y-m-d'));
    $expired = $this->db->get('subscriptions')->result();
    
    foreach ($expired as $subscription) {
        $this->Subscription_model->update($subscription->id, [
            'status' => 'expirada'
        ]);
    }
}
```

**Configurar Cron (Linux/Mac):**
```bash
# Editar crontab
crontab -e

# Adicionar linhas:
# Sincronizar assinaturas às 3h da manhã
0 3 * * * curl http://localhost/conectcorretores/cron/sync_subscriptions?token=SEU_TOKEN

# Verificar expiradas às 4h da manhã
0 4 * * * curl http://localhost/conectcorretores/cron/check_expired?token=SEU_TOKEN
```

**Configurar Cron (Windows - Task Scheduler):**
```
1. Abrir "Agendador de Tarefas"
2. Criar Tarefa Básica
3. Nome: "Sincronizar Assinaturas Stripe"
4. Gatilho: Diariamente às 3:00
5. Ação: Iniciar programa
6. Programa: curl
7. Argumentos: http://localhost/conectcorretores/cron/sync_subscriptions?token=SEU_TOKEN
```

**Token de Segurança:**
```php
// Em application/config/config.php
$config['cron_token'] = 'seu_token_secreto_aqui_123456';
```

---

## 📊 Mapeamento de Status

### **Status do Stripe → Status Local:**

| Stripe Status | Status Local | Descrição |
|---------------|--------------|-----------|
| `active` | `ativa` | Assinatura ativa e paga |
| `past_due` | `pendente` | Pagamento atrasado |
| `canceled` | `cancelada` | Assinatura cancelada |
| `unpaid` | `pendente` | Não pago |
| `incomplete` | `pendente` | Checkout incompleto |
| `incomplete_expired` | `expirada` | Checkout expirado |
| `trialing` | `trial` | Período de teste |
| `paused` | `pausada` | Assinatura pausada |

**Código:**
```php
private function _map_stripe_status($stripe_status) {
    $status_map = [
        'active' => 'ativa',
        'past_due' => 'pendente',
        'canceled' => 'cancelada',
        'unpaid' => 'pendente',
        'incomplete' => 'pendente',
        'incomplete_expired' => 'expirada',
        'trialing' => 'trial',
        'paused' => 'pausada',
    ];
    
    return $status_map[$stripe_status] ?? 'pendente';
}
```

---

## 🔄 Fluxo de Sincronização

### **Cenário 1: Usuário Faz Login**
```
1. Usuário acessa /dashboard
   ↓
2. Dashboard busca assinatura no banco
   ↓
3. Se existir, consulta no Stripe
   ↓
4. Compara dados (status, data_fim, plano)
   ↓
5. Atualiza banco se houver diferenças
   ↓
6. Exibe dashboard com dados atualizados
```

### **Cenário 2: Pagamento Falha no Stripe**
```
1. Stripe tenta cobrar cartão
   ↓
2. Pagamento falha
   ↓
3. Stripe envia webhook: invoice.payment_failed
   ↓
4. Nosso sistema recebe notificação
   ↓
5. Atualiza status para 'pendente'
   ↓
6. Usuário vê aviso no próximo login
```

### **Cenário 3: Usuário Cancela no Stripe**
```
1. Usuário acessa portal do Stripe
   ↓
2. Cancela assinatura
   ↓
3. Stripe envia webhook: customer.subscription.deleted
   ↓
4. Nosso sistema recebe notificação
   ↓
5. Atualiza status para 'cancelada'
   ↓
6. Usuário perde acesso imediatamente
```

### **Cenário 4: Cron Diário**
```
1. Cron executa às 3h da manhã
   ↓
2. Busca todas as assinaturas ativas
   ↓
3. Para cada assinatura:
   - Consulta no Stripe
   - Compara dados
   - Atualiza se necessário
   ↓
4. Gera relatório de sincronização
   ↓
5. Envia email com resumo (opcional)
```

---

## 📝 Logs de Sincronização

### **Onde ficam os logs:**
```
application/logs/log-YYYY-MM-DD.php
```

### **Exemplos de logs:**
```
INFO - Sincronização: Status alterado de 'ativa' para 'pendente'
INFO - Sincronização: Data fim alterada de '2025-10-31' para '2025-11-30'
INFO - Sincronização: Plano alterado para 'Premium' (ID: 3)
INFO - Sincronização: Assinatura ID 123 atualizada com sucesso
ERROR - Erro ao sincronizar assinatura: Invalid subscription ID
WARNING - Sincronização: Plano com stripe_price_id 'price_ABC' não encontrado
```

---

## 🧪 Como Testar

### **1. Testar Sincronização no Login:**
```
1. Acesse dashboard do Stripe
2. Mude status de uma assinatura manualmente
3. Faça login no sistema
4. Verifique se status foi atualizado
5. Confira logs em application/logs/
```

### **2. Testar Webhook:**
```
1. Use Stripe CLI para simular eventos:
   stripe trigger customer.subscription.updated
   
2. Ou use dashboard do Stripe:
   - Vá em Webhooks
   - Clique em "Send test webhook"
   - Selecione evento
   - Enviar
   
3. Verifique se banco foi atualizado
4. Confira logs
```

### **3. Testar Cron:**
```
# Via navegador (com token)
http://localhost/conectcorretores/cron/sync_subscriptions?token=SEU_TOKEN

# Via terminal
curl "http://localhost/conectcorretores/cron/sync_subscriptions?token=SEU_TOKEN"

# Via CLI (sem token)
php index.php cron sync_subscriptions
```

---

## 🔒 Segurança

### **1. Webhook:**
✅ Validação de assinatura do Stripe  
✅ Verificação de webhook_secret  
✅ Try-catch para erros  
✅ Logs de todas as requisições  

### **2. Cron:**
✅ Token de segurança obrigatório via HTTP  
✅ Verificação de CLI  
✅ Rate limiting (sleep entre requisições)  
✅ Logs detalhados  

### **3. Sincronização no Login:**
✅ Apenas para usuário logado  
✅ Try-catch para erros  
✅ Não bloqueia acesso se falhar  
✅ Logs de mudanças  

---

## 📊 Monitoramento

### **Métricas Importantes:**

1. **Taxa de Sincronização:**
   - Quantas assinaturas foram sincronizadas
   - Quantas tiveram mudanças
   - Quantas falharam

2. **Tempo de Execução:**
   - Tempo médio de sincronização
   - Tempo total do cron job

3. **Erros:**
   - Tipos de erros mais comuns
   - Assinaturas problemáticas

### **Dashboard de Monitoramento (Futuro):**
```
┌────────────────────────────────────────┐
│ Sincronização Stripe - Últimas 24h    │
├────────────────────────────────────────┤
│ Total sincronizado: 150                │
│ Atualizados: 12                        │
│ Erros: 2                               │
│ Tempo médio: 1.2s                      │
│                                         │
│ Última sincronização: 3h atrás         │
│ Próxima sincronização: em 21h          │
└────────────────────────────────────────┘
```

---

## 🚀 Próximos Passos (Futuro)

### **1. Notificações:**
- Email quando pagamento falhar
- Email quando assinatura expirar
- SMS para avisos críticos

### **2. Dashboard Admin:**
- Visualizar sincronizações
- Forçar sincronização manual
- Ver logs em tempo real

### **3. Retry Automático:**
- Tentar novamente se webhook falhar
- Queue de sincronizações pendentes

### **4. Cache:**
- Cache de dados do Stripe (5 minutos)
- Reduzir chamadas à API

---

## 📋 Checklist de Implementação

- [x] Método de sincronização no Dashboard
- [x] Método `_sync_subscription_with_stripe()`
- [x] Método `_map_stripe_status()`
- [x] Webhook melhorado com `subscription.updated`
- [x] Método `_handle_subscription_updated()`
- [x] Controller Cron criado
- [x] Método `sync_subscriptions()`
- [x] Método `check_expired()`
- [x] Método `get_by_stripe_price_id()` no Plan_model
- [x] Logs de sincronização
- [x] Documentação completa
- [ ] Configurar webhook no Stripe (manual)
- [ ] Configurar cron job no servidor (manual)
- [ ] Testar em produção

---

## ⚙️ Configuração Necessária

### **1. Stripe Dashboard:**
```
1. Acessar: https://dashboard.stripe.com/webhooks
2. Adicionar endpoint
3. URL: https://seudominio.com/planos/webhook
4. Eventos:
   - checkout.session.completed
   - customer.subscription.updated
   - customer.subscription.deleted
   - invoice.payment_succeeded
   - invoice.payment_failed
5. Copiar "Signing secret"
```

### **2. Config do Sistema:**
```php
// application/config/stripe.php
$config['stripe_webhook_secret'] = 'whsec_...';

// application/config/config.php
$config['cron_token'] = 'seu_token_secreto_123';
```

### **3. Servidor (Cron):**
```bash
# Linux/Mac
crontab -e

# Adicionar:
0 3 * * * curl "http://seudominio.com/cron/sync_subscriptions?token=SEU_TOKEN"
0 4 * * * curl "http://seudominio.com/cron/check_expired?token=SEU_TOKEN"
```

---

## 🎯 Benefícios

### **Para o Usuário:**
✅ Dados sempre atualizados  
✅ Detecção imediata de problemas  
✅ Experiência consistente  

### **Para o Sistema:**
✅ Sincronização automática  
✅ Redundância (3 estratégias)  
✅ Logs detalhados  
✅ Fácil manutenção  

### **Para o Negócio:**
✅ Reduz suporte (dados corretos)  
✅ Detecta falhas de pagamento  
✅ Previne fraudes  
✅ Melhora confiabilidade  

---

**Sistema de sincronização completo e robusto! 🚀**

Para dúvidas ou suporte: Rafael Dias - doisr.com.br
