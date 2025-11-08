# ⏰ CONFIGURAÇÃO DE CRON JOBS - TRIAL

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 08/11/2025  
**Projeto:** ConectCorretores

---

## 🎯 O QUE SÃO OS CRON JOBS DE TRIAL?

Os cron jobs são tarefas agendadas que executam automaticamente para gerenciar o ciclo de vida dos períodos de teste (trials):

1. **Processar trials expirados** - Expira trials vencidos e envia emails
2. **Enviar lembretes** - Notifica usuários sobre trials expirando
3. **Estatísticas** - Monitora conversões e performance

---

## 🔐 TOKEN DE SEGURANÇA

### **Gerar Token:**

```bash
# Gerar token aleatório
php -r "echo bin2hex(random_bytes(32));"
```

### **Configurar no CodeIgniter:**

Edite `application/config/config.php` e adicione:

```php
$config['cron_token'] = 'seu_token_gerado_aqui';
```

**⚠️ IMPORTANTE:** Nunca compartilhe este token! Ele protege seus cron jobs de acesso não autorizado.

---

## 📋 CRON JOBS NECESSÁRIOS

### **1. Processar Trials Expirados** ⚠️ OBRIGATÓRIO

**O que faz:**
- Busca trials que expiraram
- Altera status para 'expirada'
- Envia email notificando o usuário

**Frequência:** Diariamente às 2h da manhã

**Comando cPanel:**
```bash
0 2 * * * wget -q -O - "https://conectcorretores.doisr.com.br/cron/process_expired_trials?token=SEU_TOKEN" >/dev/null 2>&1
```

**Comando alternativo (curl):**
```bash
0 2 * * * curl -s "https://conectcorretores.doisr.com.br/cron/process_expired_trials?token=SEU_TOKEN" >/dev/null 2>&1
```

---

### **2. Enviar Lembretes de Trial** ⚠️ OBRIGATÓRIO

**O que faz:**
- Envia lembrete 3 dias antes do trial expirar
- Envia lembrete 1 dia antes do trial expirar

**Frequência:** Diariamente às 10h da manhã

**Comando cPanel:**
```bash
0 10 * * * wget -q -O - "https://conectcorretores.doisr.com.br/cron/send_trial_reminders?token=SEU_TOKEN" >/dev/null 2>&1
```

**Comando alternativo (curl):**
```bash
0 10 * * * curl -s "https://conectcorretores.doisr.com.br/cron/send_trial_reminders?token=SEU_TOKEN" >/dev/null 2>&1
```

---

### **3. Estatísticas de Trial** (Opcional)

**O que faz:**
- Gera relatório de trials ativos
- Calcula taxa de conversão
- Lista trials expirando em breve

**Frequência:** Semanalmente (segunda-feira às 9h)

**Comando cPanel:**
```bash
0 9 * * 1 wget -q -O - "https://conectcorretores.doisr.com.br/cron/trial_stats?token=SEU_TOKEN" > /home/conectcorretores/logs/trial_stats.log 2>&1
```

---

## 🎛️ CONFIGURAR NO CPANEL

### **Passo a Passo:**

1. **Acessar cPanel**
   - Login: https://conectcorretores.doisr.com.br:2083

2. **Ir para "Cron Jobs"**
   - Buscar por "Cron" na barra de pesquisa
   - Clicar em "Cron Jobs"

3. **Configurar Email de Notificação**
   - Adicionar: `doisr.sistemas@gmail.com`
   - Você receberá emails com o output dos cron jobs

4. **Adicionar Novo Cron Job**
   - **Common Settings:** Custom
   - **Minute:** 0
   - **Hour:** 2 (para o primeiro job)
   - **Day:** * (todos os dias)
   - **Month:** * (todos os meses)
   - **Weekday:** * (todos os dias da semana)
   - **Command:** Colar o comando wget/curl

5. **Repetir para cada cron job**

---

## 📊 FORMATO DE CRON

```
┌───────────── minuto (0 - 59)
│ ┌───────────── hora (0 - 23)
│ │ ┌───────────── dia do mês (1 - 31)
│ │ │ ┌───────────── mês (1 - 12)
│ │ │ │ ┌───────────── dia da semana (0 - 6) (Domingo=0)
│ │ │ │ │
│ │ │ │ │
* * * * * comando a ser executado
```

### **Exemplos:**

```bash
# Diariamente às 2h
0 2 * * *

# Diariamente às 10h
0 10 * * *

# Toda segunda-feira às 9h
0 9 * * 1

# A cada 6 horas
0 */6 * * *

# A cada 30 minutos
*/30 * * * *
```

---

## 🧪 TESTAR CRON JOBS

### **Via Browser:**

Acesse diretamente a URL com o token:

```
https://conectcorretores.doisr.com.br/cron/process_expired_trials?token=SEU_TOKEN
https://conectcorretores.doisr.com.br/cron/send_trial_reminders?token=SEU_TOKEN
https://conectcorretores.doisr.com.br/cron/trial_stats?token=SEU_TOKEN
```

Você verá o output em tempo real.

### **Via Terminal (SSH):**

```bash
# Testar processamento de trials expirados
curl "https://conectcorretores.doisr.com.br/cron/process_expired_trials?token=SEU_TOKEN"

# Testar envio de lembretes
curl "https://conectcorretores.doisr.com.br/cron/send_trial_reminders?token=SEU_TOKEN"

# Ver estatísticas
curl "https://conectcorretores.doisr.com.br/cron/trial_stats?token=SEU_TOKEN"
```

---

## 📧 EMAILS ENVIADOS

### **Trial Expirado:**
- **Assunto:** "Seu período de teste expirou 😢"
- **Template:** `trial_expired.php`
- **Quando:** Quando o trial expira

### **Trial Expirando (3 dias):**
- **Assunto:** "Seu período de teste termina em 3 dias ⏰"
- **Template:** `trial_expiring.php`
- **Quando:** 3 dias antes de expirar

### **Trial Expirando (1 dia):**
- **Assunto:** "Seu período de teste termina em 1 dia ⏰"
- **Template:** `trial_expiring.php`
- **Quando:** 1 dia antes de expirar

---

## 📝 LOGS

### **Ver Logs do Cron:**

```bash
# Via SSH
tail -f /home/conectcorretores/logs/trial_stats.log

# Ou redirecionar output para arquivo
0 2 * * * wget -q -O - "URL" >> /home/conectcorretores/logs/cron_trials.log 2>&1
```

### **Ver Logs do CodeIgniter:**

```bash
tail -f /home/conectcorretores/public_html/application/logs/log-*.php
```

---

## 🔧 TROUBLESHOOTING

### **Cron não está executando:**

1. **Verificar se o cron está ativo no cPanel**
   - Ir em "Cron Jobs"
   - Verificar se aparece na lista

2. **Verificar permissões**
   ```bash
   chmod 755 /home/conectcorretores/public_html/index.php
   ```

3. **Verificar token**
   - Confirmar que o token está correto
   - Verificar em `application/config/config.php`

4. **Testar manualmente**
   - Acessar a URL no browser
   - Ver se retorna erro ou executa

### **Emails não estão sendo enviados:**

1. **Verificar configuração SMTP**
   - `application/config/email.php`
   - Testar com `/test_email`

2. **Verificar logs**
   ```bash
   tail -f application/logs/log-*.php
   ```

3. **Verificar se BREVO está ativo**
   - Acessar painel: https://app.brevo.com/
   - Ver se há erros ou bloqueios

### **Token inválido:**

```
❌ Erro 404 - Not Found
```

**Solução:**
- Verificar se o token na URL está correto
- Verificar se `$config['cron_token']` está configurado

---

## 📊 MONITORAMENTO

### **Verificar se está funcionando:**

1. **Acessar estatísticas:**
   ```
   https://conectcorretores.doisr.com.br/cron/trial_stats?token=SEU_TOKEN
   ```

2. **Verificar emails no BREVO:**
   - https://app.brevo.com/
   - Ver emails enviados nas últimas 24h

3. **Verificar banco de dados:**
   ```sql
   -- Trials ativos
   SELECT COUNT(*) FROM subscriptions WHERE status = 'trial' AND trial_ends_at >= NOW();
   
   -- Trials expirados hoje
   SELECT COUNT(*) FROM subscriptions WHERE status = 'expirada' AND DATE(updated_at) = CURDATE();
   
   -- Conversões de trial
   SELECT COUNT(*) FROM subscriptions WHERE converted_from_trial = 1;
   ```

---

## ✅ CHECKLIST DE CONFIGURAÇÃO

- [ ] Gerar token de segurança
- [ ] Configurar token em `config.php`
- [ ] Configurar email BREVO em `email.php`
- [ ] Adicionar cron "Processar Trials Expirados" (2h)
- [ ] Adicionar cron "Enviar Lembretes" (10h)
- [ ] Adicionar cron "Estatísticas" (opcional)
- [ ] Testar cada cron manualmente
- [ ] Verificar se emails estão sendo enviados
- [ ] Configurar email de notificação no cPanel
- [ ] Monitorar logs por 7 dias

---

## 🚀 COMANDOS FINAIS PARA CPANEL

### **Cron 1 - Processar Trials Expirados (OBRIGATÓRIO):**
```
0 2 * * * wget -q -O - "https://conectcorretores.doisr.com.br/cron/process_expired_trials?token=SEU_TOKEN" >/dev/null 2>&1
```

### **Cron 2 - Enviar Lembretes (OBRIGATÓRIO):**
```
0 10 * * * wget -q -O - "https://conectcorretores.doisr.com.br/cron/send_trial_reminders?token=SEU_TOKEN" >/dev/null 2>&1
```

### **Cron 3 - Estatísticas (OPCIONAL):**
```
0 9 * * 1 wget -q -O - "https://conectcorretores.doisr.com.br/cron/trial_stats?token=SEU_TOKEN" > /home/conectcorretores/logs/trial_stats.log 2>&1
```

---

**⚠️ LEMBRE-SE:** Substitua `SEU_TOKEN` pelo token real gerado!

---

**Última atualização:** 08/11/2025
