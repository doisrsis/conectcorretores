# 🔍 Habilitar Logs de Debug

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 06/11/2025

---

## 🎯 Objetivo

Habilitar logs detalhados para debugar webhooks e envio de emails.

---

## ⚙️ Configuração

### **Passo 1: Habilitar Logs no CodeIgniter**

Editar: `application/config/config.php`

Procurar por `log_threshold` e alterar para:

```php
$config['log_threshold'] = 4; // 0=Desabilitado, 1=Error, 2=Debug, 3=Info, 4=All
```

---

### **Passo 2: Verificar Permissões**

Garantir que a pasta de logs tem permissão de escrita:

```bash
# Windows (não precisa fazer nada, já tem permissão)

# Linux/Mac
chmod 777 application/logs
```

---

### **Passo 3: Testar Assinatura**

1. **Fazer nova assinatura**
2. **Completar checkout**
3. **Aguardar webhook**

---

### **Passo 4: Ver Logs**

Abrir arquivo de log:
```
application/logs/log-2025-11-06.php
```

Procurar por:
```
========== WEBHOOK RECEBIDO ==========
```

---

## 📋 O Que os Logs Mostram

### **Webhook Recebido:**
```
========== WEBHOOK RECEBIDO ==========
Webhook Secret configurado: SIM
Evento recebido: checkout.session.completed
Processando checkout.session.completed
```

### **Processamento:**
```
--- Iniciando _handle_checkout_completed ---
User ID: 123
Subscription ID: sub_xxxxx
Customer ID: cus_xxxxx
Plan ID do metadata: 1
Plano encontrado: Profissional
```

### **Envio de Email:**
```
--- Tentando enviar email de assinatura ativada ---
Usuário encontrado: usuario@email.com
Assinatura encontrada: SIM
Chamando email_lib->send_subscription_activated()
Email enviado: SUCESSO
```

### **Fim:**
```
Webhook: Imóveis reativados para usuário ID: 123
--- Fim _handle_checkout_completed ---
Webhook processado com sucesso
========================================
```

---

## 🐛 Possíveis Erros

### **Erro 1: Plan ID não encontrado**
```
Plan ID não encontrado no metadata!
```
**Solução:** Verificar se metadata está sendo enviado no checkout

### **Erro 2: Usuário não encontrado**
```
Usuário encontrado: NÃO
```
**Solução:** Verificar se user_id está correto

### **Erro 3: Email falhou**
```
Email enviado: FALHA
```
**Solução:** Verificar configurações SMTP

---

## 📊 Análise de Logs

### **Fluxo Completo Esperado:**

1. ✅ Webhook recebido
2. ✅ Evento identificado
3. ✅ User ID encontrado
4. ✅ Plan ID encontrado
5. ✅ Assinatura criada
6. ✅ Imóveis reativados
7. ✅ Usuário encontrado
8. ✅ Assinatura encontrada
9. ✅ Email enviado

**Se algum passo falhar, o log mostrará onde!**

---

## 🔍 Comandos Úteis

### **Ver últimas linhas do log:**
```bash
# Windows (PowerShell)
Get-Content application/logs/log-2025-11-06.php -Tail 50

# Linux/Mac
tail -f application/logs/log-2025-11-06.php
```

### **Buscar por erro:**
```bash
# Windows (PowerShell)
Select-String -Path application/logs/log-2025-11-06.php -Pattern "ERROR"

# Linux/Mac
grep "ERROR" application/logs/log-2025-11-06.php
```

---

## ⚠️ IMPORTANTE

Após debugar, **desabilitar logs em produção**:

```php
$config['log_threshold'] = 1; // Apenas erros
```

Logs detalhados podem:
- Ocupar muito espaço em disco
- Expor informações sensíveis
- Reduzir performance

---

**Logs habilitados = Debug facilitado! 🔍**

Para suporte: Rafael Dias - doisr.com.br
