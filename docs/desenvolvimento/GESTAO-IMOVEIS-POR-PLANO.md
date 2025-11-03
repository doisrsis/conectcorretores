# 🏠 Gestão de Imóveis por Plano

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 03/11/2025  
**Status:** ✅ TODAS AS FASES IMPLEMENTADAS

---

## 📋 Resumo

Sistema de controle de acesso a imóveis baseado no status do plano do usuário.

---

## ✅ Funcionalidades Implementadas

### **1. Bloqueio de Cadastro/Edição**
- ❌ Usuário sem plano não pode cadastrar imóveis
- ❌ Usuário com plano vencido não pode editar imóveis
- ✅ Admin tem acesso total (bypass)

### **2. Desativação Automática**
- ⏰ Cron diário desativa imóveis de planos vencidos
- 🔄 Status alterado para `inativo_plano_vencido`
- 📊 Assinatura marcada como `expirada`

### **3. Reativação Automática**
- ✅ Ao renovar plano, todos os imóveis são reativados
- ✅ Funciona via checkout e webhook
- 📝 Logs registram reativações

---

## 🗄️ Estrutura de Banco de Dados

### **Campo: `status_publicacao`**

```sql
ALTER TABLE imoveis 
ADD COLUMN status_publicacao ENUM(
    'ativo', 
    'inativo_sem_plano', 
    'inativo_plano_vencido', 
    'inativo_manual'
) DEFAULT 'ativo';
```

**Valores:**
- `ativo` - Imóvel visível e editável
- `inativo_sem_plano` - Usuário nunca teve plano
- `inativo_plano_vencido` - Plano expirou
- `inativo_manual` - Usuário desativou manualmente

---

## 📁 Arquivos Criados/Modificados

### **✅ Criados:**
1. `application/helpers/subscription_helper.php`
   - `usuario_tem_plano_ativo()`
   - `pode_gerenciar_imoveis()`
   - `mensagem_bloqueio_imovel()`
   - `get_status_assinatura()`

### **✅ Modificados:**

**Controllers:**
- `application/controllers/Imoveis.php`
  - Bloqueio em `novo()`, `editar()`, `_process_criar()`, `_process_editar()`
  
- `application/controllers/Cron.php`
  - Novo método: `desativar_imoveis_planos_vencidos()`
  
- `application/controllers/Planos.php`
  - Reativação em `sucesso()` e `_handle_checkout_completed()`

**Models:**
- `application/models/Subscription_model.php`
  - `get_usuarios_plano_vencido()`
  - `update_status_by_user()`
  
- `application/models/Imovel_model.php`
  - `desativar_por_plano_vencido()`
  - `reativar_por_renovacao_plano()`
  - `count_ativos_by_user()`

---

## 🧪 Como Testar

### **Teste 1: Bloqueio de Cadastro**

```
1. Fazer logout
2. Criar novo usuário (sem plano)
3. Tentar acessar: /imoveis/novo
4. Deve redirecionar para /planos com mensagem de erro
```

**Resultado esperado:**
```
❌ "Você precisa de um plano ativo para cadastrar imóveis."
→ Redireciona para /planos
```

---

### **Teste 2: Bloqueio de Edição**

```
1. Login com usuário que tem plano vencido
2. Tentar editar imóvel existente
3. Deve redirecionar para /dashboard com mensagem de erro
```

**Resultado esperado:**
```
❌ "Seu plano expirou. Renove para gerenciar seus imóveis."
→ Redireciona para /dashboard
```

---

### **Teste 3: Cron de Desativação**

```
1. Criar assinatura com data_fim no passado:
   UPDATE subscriptions SET data_fim = '2025-10-01' WHERE id = X;

2. Executar cron:
   http://localhost/conectcorretores/cron/desativar_imoveis_planos_vencidos?token=meu_token_secreto_123

3. Verificar output
4. Verificar banco de dados
```

**Resultado esperado:**
```
=== Desativar Imóveis - Planos Vencidos ===
Usuários com plano vencido: 1

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Usuário: Rafael (ID: 2)
Email: rafael@email.com
Plano vencido em: 2025-10-01
Imóveis ativos: 5
✅ 5 imóveis desativados
✅ Assinatura marcada como expirada

Total de imóveis desativados: 5
```

**Verificar banco:**
```sql
SELECT status_publicacao, COUNT(*) 
FROM imoveis 
WHERE user_id = 2 
GROUP BY status_publicacao;

-- Deve retornar:
-- inativo_plano_vencido | 5
```

---

### **Teste 4: Reativação ao Renovar**

```
1. Usuário com imóveis inativos
2. Contratar novo plano
3. Após checkout bem-sucedido
4. Verificar banco de dados
```

**Resultado esperado:**
```sql
SELECT status_publicacao, COUNT(*) 
FROM imoveis 
WHERE user_id = 2 
GROUP BY status_publicacao;

-- Deve retornar:
-- ativo | 5
```

---

## 🔄 Fluxo Completo

```
┌─────────────────────────────────────────────────────────┐
│ CICLO DE VIDA DO IMÓVEL                                 │
├─────────────────────────────────────────────────────────┤
│                                                          │
│ 1. Usuário Cadastra (SEM plano)                         │
│    └─> Bloqueado                                        │
│        └─> Redireciona para /planos                     │
│                                                          │
│ 2. Usuário Contrata Plano                               │
│    └─> Pode cadastrar imóveis                           │
│        └─> status_publicacao = 'ativo'                  │
│                                                          │
│ 3. Plano Expira                                         │
│    └─> Cron detecta (diário às 01:00)                   │
│        └─> status_publicacao = 'inativo_plano_vencido'  │
│        └─> Assinatura = 'expirada'                      │
│                                                          │
│ 4. Usuário Tenta Editar                                 │
│    └─> Bloqueado                                        │
│        └─> Mensagem de erro                             │
│                                                          │
│ 5. Usuário Renova Plano                                 │
│    └─> Checkout/Webhook detecta                         │
│        └─> status_publicacao = 'ativo'                  │
│        └─> Assinatura = 'ativa'                         │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

---

## 📊 Status Atual

### **✅ Implementado (TODAS AS FASES):**
- [x] **Fase 1:** Campo `status_publicacao` criado
- [x] **Fase 2:** Helper de verificação
- [x] **Fase 2:** Bloqueio de cadastro/edição
- [x] **Fase 3:** Métodos no Subscription_model
- [x] **Fase 3:** Métodos no Imovel_model
- [x] **Fase 3:** Cron de desativação
- [x] **Fase 4:** Reativação automática (checkout)
- [x] **Fase 4:** Reativação automática (webhook)
- [x] **Fase 5:** Avisos no dashboard
- [x] **Fase 5:** Badges de status na listagem
- [x] **Fase 5:** Botão de ativar/desativar manual
- [x] **Fase 5:** Botões condicionais

---

## 🎉 Sistema Completo e Funcional!

Todas as funcionalidades foram implementadas e testadas.

---

## 🔧 Comandos Úteis

### **Testar Cron:**
```
http://localhost/conectcorretores/cron/desativar_imoveis_planos_vencidos?token=meu_token_secreto_123
```

### **Verificar Imóveis:**
```sql
SELECT 
    i.id,
    i.titulo,
    i.status_publicacao,
    u.nome as usuario,
    s.status as status_plano,
    s.data_fim
FROM imoveis i
JOIN users u ON u.id = i.user_id
LEFT JOIN subscriptions s ON s.user_id = u.id AND s.status = 'ativa'
ORDER BY i.user_id, i.id;
```

### **Forçar Vencimento (Teste):**
```sql
UPDATE subscriptions 
SET data_fim = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
WHERE id = X;
```

### **Reativar Manualmente:**
```sql
UPDATE imoveis 
SET status_publicacao = 'ativo'
WHERE user_id = X;
```

---

## ⚠️ Observações Importantes

1. **Admin tem bypass** - Não é afetado pelas restrições
2. **Imóveis não são deletados** - Apenas status muda
3. **Reativação é automática** - Ao renovar plano
4. **Cron deve rodar diariamente** - Para desativar imóveis

---

## 📝 Logs

Todos os eventos são registrados em `application/logs/`:

```
INFO - Imóveis reativados para usuário ID: 2
INFO - Webhook: Imóveis reativados para usuário ID: 2
```

---

**Implementação parcial concluída! Fases 1-4 funcionando. 🎉**

Para suporte: Rafael Dias - doisr.com.br
