# 🧪 Como Testar a Sincronização com Stripe

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 19/10/2025

---

## ⚠️ IMPORTANTE: Sincronização Desabilitada Temporariamente

A sincronização automática no login foi **DESABILITADA** para evitar sobrescrever as datas corretas.

**Motivo:** Quando você cria uma assinatura, o Stripe pode demorar alguns segundos para processar e definir as datas corretas. Se a sincronização rodar imediatamente, pode pegar dados incompletos.

**Solução:** Configure o webhook primeiro (método mais confiável) e depois habilite a sincronização no login.

---

## 🎯 Ordem Recomendada de Testes

1. ✅ **Webhook** (Mais importante - Tempo real)
2. ✅ **Cron Job** (Sincronização em lote)
3. ✅ **Login** (Habilitar depois que webhook estiver funcionando)

---

## 📋 TESTE 1: Webhook do Stripe (RECOMENDADO)

### **O que é:**
O Stripe envia notificações automáticas para nosso sistema quando algo acontece (nova assinatura, cancelamento, falha de pagamento, etc.)

### **Pré-requisitos:**
- ✅ Sistema acessível pela internet (não pode ser apenas localhost)
- ✅ URL pública (ex: https://seudominio.com)
- ✅ OU usar ngrok para expor localhost

---

### **Opção A: Com Domínio Público (Produção)**

#### **1. Configurar Webhook no Stripe:**

```
1. Acessar: https://dashboard.stripe.com/test/webhooks
2. Clicar em "Add endpoint"
3. URL do endpoint: https://seudominio.com/planos/webhook
4. Selecionar eventos para escutar:
   ✅ checkout.session.completed
   ✅ customer.subscription.created
   ✅ customer.subscription.updated
   ✅ customer.subscription.deleted
   ✅ invoice.payment_succeeded
   ✅ invoice.payment_failed
5. Clicar em "Add endpoint"
6. Copiar o "Signing secret" (começa com whsec_...)
```

#### **2. Adicionar Secret no Sistema:**

```php
// Em: application/config/stripe.php
$config['stripe_webhook_secret'] = 'whsec_SEU_SECRET_AQUI';
```

#### **3. Testar Webhook:**

**Opção 1 - Teste Manual no Dashboard:**
```
1. No Stripe Dashboard, vá em: Webhooks
2. Clique no endpoint que você criou
3. Clique em "Send test webhook"
4. Selecione: customer.subscription.updated
5. Clique em "Send test webhook"
6. Verifique se apareceu "Success" (código 200)
```

**Opção 2 - Criar Assinatura Real:**
```
1. Acesse: http://seudominio.com/planos
2. Escolha um plano
3. Clique em "Assinar Agora"
4. Preencha dados do cartão de teste:
   - Número: 4242 4242 4242 4242
   - Data: 12/34
   - CVV: 123
5. Confirme pagamento
6. Stripe enviará webhook automaticamente
7. Verifique no banco se dados foram salvos
```

**Opção 3 - Simular Eventos:**
```
1. No Stripe Dashboard: Webhooks → Seu endpoint
2. Clique em "Send test webhook"
3. Escolha evento (ex: invoice.payment_failed)
4. Enviar
5. Verificar logs em: application/logs/
```

---

### **Opção B: Com ngrok (Desenvolvimento Local)**

#### **1. Instalar ngrok:**
```bash
# Windows
1. Baixar: https://ngrok.com/download
2. Extrair ngrok.exe
3. Abrir terminal na pasta do ngrok
```

#### **2. Expor localhost:**
```bash
# Executar ngrok
ngrok http 80

# Você verá algo como:
# Forwarding: https://abc123.ngrok.io -> http://localhost:80
```

#### **3. Configurar Webhook:**
```
1. Copiar URL do ngrok: https://abc123.ngrok.io
2. No Stripe Dashboard: Webhooks → Add endpoint
3. URL: https://abc123.ngrok.io/conectcorretores/planos/webhook
4. Selecionar eventos (mesmos de antes)
5. Copiar signing secret
6. Adicionar em config/stripe.php
```

#### **4. Testar:**
```
1. Deixar ngrok rodando
2. Criar assinatura em: http://localhost/conectcorretores/planos
3. Stripe enviará webhook para ngrok
4. ngrok encaminha para seu localhost
5. Verificar logs
```

#### **5. Ver Requisições no ngrok:**
```
1. Abrir: http://localhost:4040
2. Ver todas as requisições HTTP
3. Inspecionar payload do webhook
```

---

### **Opção C: Stripe CLI (Mais Fácil para Testes)**

#### **1. Instalar Stripe CLI:**
```bash
# Windows
1. Baixar: https://github.com/stripe/stripe-cli/releases
2. Extrair stripe.exe
3. Adicionar ao PATH ou usar direto da pasta
```

#### **2. Login no Stripe:**
```bash
stripe login
# Abrirá navegador para autorizar
```

#### **3. Escutar Webhooks:**
```bash
# Encaminhar webhooks do Stripe para localhost
stripe listen --forward-to http://localhost/conectcorretores/planos/webhook

# Você verá:
# > Ready! Your webhook signing secret is whsec_...
# Copie esse secret e adicione em config/stripe.php
```

#### **4. Testar Eventos:**

**Criar assinatura de teste:**
```bash
stripe trigger checkout.session.completed
```

**Simular pagamento bem-sucedido:**
```bash
stripe trigger invoice.payment_succeeded
```

**Simular falha de pagamento:**
```bash
stripe trigger invoice.payment_failed
```

**Simular atualização de assinatura:**
```bash
stripe trigger customer.subscription.updated
```

**Simular cancelamento:**
```bash
stripe trigger customer.subscription.deleted
```

#### **5. Ver Logs:**
```
1. Terminal do Stripe CLI mostrará eventos recebidos
2. Verificar application/logs/ no sistema
3. Verificar banco de dados se foi atualizado
```

---

## 📋 TESTE 2: Cron Job (Sincronização Diária)

### **O que é:**
Script que roda automaticamente (ou manualmente) para sincronizar todas as assinaturas de uma vez.

### **Teste Manual (Navegador):**

#### **1. Definir Token de Segurança:**
```php
// Em: application/config/config.php
$config['cron_token'] = 'meu_token_secreto_123';
```

#### **2. Executar via Navegador:**
```
http://localhost/conectcorretores/cron/sync_subscriptions?token=meu_token_secreto_123
```

#### **3. O que você verá:**
```
=== Sincronização de Assinaturas com Stripe ===
Início: 2025-10-19 17:30:00

Total de assinaturas para sincronizar: 5

[1] Sincronizando assinatura ID 1 (User: João Silva)...
  ✓ Já está sincronizado

[2] Sincronizando assinatura ID 2 (User: Maria Santos)...
  📝 Status: ativa → pendente
  ✅ Atualizado com sucesso!

[3] Sincronizando assinatura ID 3 (User: Pedro Costa)...
  📅 Data fim: 2025-10-31 → 2025-11-30
  ✅ Atualizado com sucesso!

=== Resumo ===
Total processado: 3
Atualizados: 2
Erros: 0
Tempo: 4.5s
Fim: 2025-10-19 17:30:05
```

#### **4. Verificar Banco de Dados:**
```sql
SELECT id, user_id, status, data_fim, updated_at 
FROM subscriptions 
ORDER BY updated_at DESC;
```

---

### **Teste Manual (Terminal):**

#### **Windows (PowerShell):**
```powershell
curl "http://localhost/conectcorretores/cron/sync_subscriptions?token=meu_token_secreto_123"
```

#### **Linux/Mac:**
```bash
curl "http://localhost/conectcorretores/cron/sync_subscriptions?token=meu_token_secreto_123"
```

---

### **Agendar Cron (Produção):**

#### **Linux/Mac (crontab):**
```bash
# Editar crontab
crontab -e

# Adicionar linha (executar às 3h da manhã):
0 3 * * * curl "http://seudominio.com/cron/sync_subscriptions?token=SEU_TOKEN"
```

#### **Windows (Agendador de Tarefas):**
```
1. Abrir "Agendador de Tarefas"
2. Criar Tarefa Básica
3. Nome: "Sincronizar Assinaturas Stripe"
4. Gatilho: Diariamente às 3:00
5. Ação: Iniciar programa
6. Programa: C:\Windows\System32\curl.exe
7. Argumentos: http://seudominio.com/cron/sync_subscriptions?token=SEU_TOKEN
8. Salvar
```

---

### **Testar Verificação de Expiradas:**
```
http://localhost/conectcorretores/cron/check_expired?token=meu_token_secreto_123
```

**O que faz:**
- Busca assinaturas com `status = 'ativa'` e `data_fim < hoje`
- Muda status para `'expirada'`

---

## 📋 TESTE 3: Sincronização no Login (DESABILITADA)

### **Status Atual:**
⚠️ **DESABILITADA** temporariamente para evitar sobrescrever datas.

### **Quando Habilitar:**
✅ Depois que webhook estiver configurado e funcionando  
✅ Depois de testar e confirmar que webhook atualiza corretamente  

### **Como Habilitar:**

```php
// Em: application/controllers/Dashboard.php
// Linha ~45-52

// ANTES (desabilitado):
// if ($data['subscription']) {
//     $this->_sync_subscription_with_stripe($data['subscription']);
//     $data['subscription'] = $this->Subscription_model->get_active_by_user($user_id);
// }

// DEPOIS (habilitado):
if ($data['subscription']) {
    $this->_sync_subscription_with_stripe($data['subscription']);
    $data['subscription'] = $this->Subscription_model->get_active_by_user($user_id);
}
```

### **Como Testar (Depois de Habilitar):**

#### **1. Fazer Login:**
```
http://localhost/conectcorretores/login
```

#### **2. Acessar Dashboard:**
```
http://localhost/conectcorretores/dashboard
```

#### **3. Verificar Logs:**
```
application/logs/log-2025-10-19.php
```

**Procurar por:**
```
INFO - Sincronização: Status alterado de 'ativa' para 'pendente'
INFO - Sincronização: Data fim alterada de '2025-10-31' para '2025-11-30'
INFO - Sincronização: Plano alterado para 'Premium' (ID: 3)
INFO - Sincronização: Assinatura ID 123 atualizada com sucesso
```

#### **4. Verificar Banco:**
```sql
SELECT id, status, data_fim, updated_at 
FROM subscriptions 
WHERE user_id = SEU_USER_ID;
```

---

## 🔍 Como Verificar se Funcionou

### **1. Logs do Sistema:**
```
application/logs/log-YYYY-MM-DD.php
```

**Procurar por:**
- `Sincronização:`
- `Webhook:`
- `Cron:`

### **2. Banco de Dados:**
```sql
-- Ver última atualização
SELECT * FROM subscriptions ORDER BY updated_at DESC LIMIT 5;

-- Ver assinaturas ativas
SELECT * FROM subscriptions WHERE status = 'ativa';

-- Ver histórico de mudanças (se tiver campo updated_at)
SELECT id, user_id, status, data_fim, updated_at 
FROM subscriptions 
ORDER BY updated_at DESC;
```

### **3. Dashboard do Stripe:**
```
1. Acessar: https://dashboard.stripe.com/test/subscriptions
2. Ver assinaturas criadas
3. Clicar em uma assinatura
4. Ver "Events" (eventos)
5. Verificar se webhooks foram enviados
```

### **4. Webhook Logs (Stripe):**
```
1. Acessar: https://dashboard.stripe.com/test/webhooks
2. Clicar no seu endpoint
3. Ver "Recent deliveries"
4. Verificar status (200 = sucesso, 400/500 = erro)
5. Clicar em um evento para ver detalhes
```

---

## 🐛 Troubleshooting

### **Problema: Webhook não recebe eventos**

**Soluções:**
1. ✅ Verificar se URL está acessível pela internet
2. ✅ Verificar se ngrok está rodando (se usando)
3. ✅ Verificar se endpoint está correto: `/planos/webhook`
4. ✅ Verificar logs do Stripe (Recent deliveries)
5. ✅ Testar com "Send test webhook"

### **Problema: Erro 400 no webhook**

**Soluções:**
1. ✅ Verificar se `stripe_webhook_secret` está correto
2. ✅ Verificar se secret está em `config/stripe.php`
3. ✅ Verificar logs: `application/logs/`

### **Problema: Cron retorna 404**

**Soluções:**
1. ✅ Verificar se URL está correta: `/cron/sync_subscriptions`
2. ✅ Verificar se token está correto
3. ✅ Verificar se arquivo `Cron.php` existe

### **Problema: Datas sendo sobrescritas**

**Soluções:**
1. ✅ Desabilitar sincronização no login (já feito)
2. ✅ Configurar webhook primeiro
3. ✅ Verificar se `current_period_end` está correto no Stripe
4. ✅ Adicionar delay após criar assinatura

---

## 📊 Cenários de Teste Completos

### **Cenário 1: Nova Assinatura**
```
1. Criar assinatura em /planos
2. Preencher dados do cartão
3. Confirmar pagamento
4. Stripe envia webhook: checkout.session.completed
5. Sistema cria assinatura no banco
6. Verificar: status = 'ativa', datas corretas
```

### **Cenário 2: Pagamento Falha**
```
1. No Stripe CLI: stripe trigger invoice.payment_failed
2. Webhook recebe evento
3. Sistema atualiza: status = 'pendente'
4. Verificar banco de dados
```

### **Cenário 3: Upgrade de Plano**
```
1. Fazer upgrade em /planos
2. Stripe envia webhook: customer.subscription.updated
3. Sistema atualiza plan_id
4. Verificar banco de dados
```

### **Cenário 4: Cancelamento**
```
1. Cancelar assinatura
2. Stripe envia webhook: customer.subscription.deleted
3. Sistema atualiza: status = 'cancelada'
4. Verificar banco de dados
```

### **Cenário 5: Sincronização Diária**
```
1. Mudar algo no Stripe manualmente
2. Executar cron: /cron/sync_subscriptions?token=XXX
3. Verificar se banco foi atualizado
4. Ver relatório no navegador
```

---

## ✅ Checklist de Testes

### **Webhook:**
- [ ] Configurado no Stripe Dashboard
- [ ] Secret adicionado em config/stripe.php
- [ ] Testado com "Send test webhook"
- [ ] Testado com assinatura real
- [ ] Logs verificados
- [ ] Banco de dados atualizado

### **Cron:**
- [ ] Token configurado em config.php
- [ ] Executado manualmente via navegador
- [ ] Relatório exibido corretamente
- [ ] Banco de dados atualizado
- [ ] Agendado no servidor (produção)

### **Login (Depois de Habilitar):**
- [ ] Código descomentado
- [ ] Login realizado
- [ ] Dashboard acessado
- [ ] Logs verificados
- [ ] Banco de dados atualizado

---

## 🎯 Recomendação Final

**Para desenvolvimento:**
1. ✅ Use **Stripe CLI** (mais fácil)
2. ✅ Teste **webhook** primeiro
3. ✅ Teste **cron** manualmente
4. ✅ Habilite **login** depois

**Para produção:**
1. ✅ Configure **webhook** no Stripe Dashboard
2. ✅ Agende **cron** no servidor
3. ✅ Habilite **sincronização no login**
4. ✅ Monitore **logs** regularmente

---

**Qualquer dúvida, consulte este guia! 🚀**

Para suporte: Rafael Dias - doisr.com.br
