# 🚀 Sistema de Upgrade/Downgrade de Planos

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 19/10/2025  
**Versão:** 1.0

---

## ✅ Implementação Completa

### **Problema Resolvido:**
Antes, quando o usuário tinha uma assinatura ativa, todos os outros planos ficavam bloqueados com a mensagem "Cancele sua assinatura primeiro", impedindo upgrades ou downgrades.

### **Solução Implementada:**
Sistema inteligente que compara preços dos planos e permite upgrade/downgrade direto, sem necessidade de cancelar a assinatura atual.

---

## 🎨 Funcionalidades Implementadas

### **1. Botões Inteligentes na Página de Planos**

#### **Plano Atual:**
```
┌─────────────────────────────────┐
│ Plano Profissional              │
│ R$ 99,90/mensal                 │
│                                  │
│ [✓ Plano Atual] (cinza, disabled)│
└─────────────────────────────────┘
```

#### **Upgrade (Plano Mais Caro):**
```
┌─────────────────────────────────┐
│ Plano Premium                   │
│ R$ 199,90/mensal                │
│                                  │
│ +R$ 100,00/mês (verde)          │
│ [🚀 Fazer Upgrade] (verde, ativo)│
└─────────────────────────────────┘
```

#### **Downgrade (Plano Mais Barato):**
```
┌─────────────────────────────────┐
│ Plano Básico                    │
│ R$ 49,90/mensal                 │
│                                  │
│ Economize R$ 50,00/mês (amarelo)│
│ [⬇️ Fazer Downgrade] (amarelo)   │
└─────────────────────────────────┘
```

---

## 🔧 Arquivos Modificados

### **1. View - `application/views/planos/index.php`**

**Mudanças:**
- ✅ Lógica de comparação de preços
- ✅ Botões diferentes para upgrade/downgrade
- ✅ Exibição da diferença de valor
- ✅ Funções JavaScript `iniciarUpgrade()` e `iniciarDowngrade()`

**Código:**
```php
<?php if ($current_subscription && $current_subscription->plan_id == $plan->id): ?>
    <!-- Plano Atual -->
    <button disabled>Plano Atual</button>
    
<?php elseif ($current_subscription && $plan->preco > $current_subscription->plan_preco): ?>
    <!-- Upgrade -->
    <div>+R$ XX,XX/mês</div>
    <button onclick="iniciarUpgrade()">Fazer Upgrade</button>
    
<?php elseif ($current_subscription && $plan->preco < $current_subscription->plan_preco): ?>
    <!-- Downgrade -->
    <div>Economize R$ XX,XX/mês</div>
    <button onclick="iniciarDowngrade()">Fazer Downgrade</button>
<?php endif; ?>
```

---

### **2. Controller - `application/controllers/Planos.php`**

**Novos Métodos:**

#### **`upgrade()` - Fazer Upgrade de Plano**
```php
public function upgrade() {
    // 1. Validar login
    // 2. Buscar assinatura atual
    // 3. Buscar novo plano
    // 4. Validar se é upgrade (preço maior)
    // 5. Atualizar no Stripe (com proporcional)
    // 6. Atualizar no banco de dados
    // 7. Retornar JSON success
}
```

**Características:**
- ✅ Valida se o novo plano é mais caro
- ✅ Atualiza no Stripe com cálculo proporcional automático
- ✅ Atualiza no banco de dados
- ✅ Retorna JSON para AJAX

#### **`downgrade()` - Fazer Downgrade de Plano**
```php
public function downgrade() {
    // 1. Validar login
    // 2. Buscar assinatura atual
    // 3. Buscar novo plano
    // 4. Validar se é downgrade (preço menor)
    // 5. Verificar limite de imóveis
    // 6. Inativar imóveis se necessário
    // 7. Atualizar no Stripe
    // 8. Atualizar no banco de dados
    // 9. Retornar JSON com mensagem
}
```

**Características:**
- ✅ Valida se o novo plano é mais barato
- ✅ Verifica se usuário tem mais imóveis que o limite do novo plano
- ✅ **Inativa TODOS os imóveis** se exceder o limite
- ✅ Retorna mensagem para usuário reativar imóveis manualmente
- ✅ Atualiza no Stripe com cálculo proporcional

---

### **3. Biblioteca Stripe - `application/libraries/Stripe_lib.php`**

**Novo Método:**

#### **`update_subscription()` - Atualizar Assinatura**
```php
public function update_subscription($subscription_id, $new_price_id) {
    // Atualizar item da assinatura com novo preço
    \Stripe\Subscription::update($subscription_id, [
        'items' => [
            ['id' => $item_id, 'price' => $new_price_id]
        ],
        'proration_behavior' => 'always_invoice'
    ]);
}
```

**Características:**
- ✅ Atualiza o preço da assinatura no Stripe
- ✅ `proration_behavior: always_invoice` = Calcula proporcional e cobra/credita **imediatamente**
- ✅ Stripe gera invoice automático com o valor proporcional

---

### **4. Model Imóveis - `application/models/Imovel_model.php`**

**Novos Métodos:**

#### **`count_by_user()` - Contar Imóveis do Usuário**
```php
public function count_by_user($user_id, $only_active = true) {
    $this->db->where('user_id', $user_id);
    if ($only_active) {
        $this->db->where('ativo', 1);
    }
    return $this->db->count_all_results('imoveis');
}
```

#### **`inativar_todos_by_user()` - Inativar Todos os Imóveis**
```php
public function inativar_todos_by_user($user_id) {
    $this->db->where('user_id', $user_id);
    return $this->db->update('imoveis', ['ativo' => 0]);
}
```

**Uso:**
- Quando usuário faz downgrade para plano com limite menor
- Todos os imóveis são inativados
- Usuário deve reativar manualmente até o limite

---

## 🔄 Fluxo de Upgrade

```
1. Usuário clica em "Fazer Upgrade"
   ↓
2. JavaScript chama /planos/upgrade (AJAX)
   ↓
3. Controller valida:
   - Usuário logado?
   - Tem assinatura ativa?
   - Novo plano existe?
   - É realmente upgrade (preço maior)?
   ↓
4. Atualiza no Stripe:
   - Troca o price_id da assinatura
   - Stripe calcula proporcional
   - Cobra diferença imediatamente
   ↓
5. Atualiza no banco de dados:
   - Muda plan_id da subscription
   - Atualiza updated_at
   ↓
6. Retorna JSON success
   ↓
7. JavaScript redireciona para /dashboard
   ↓
8. Usuário vê mensagem: "✅ Upgrade realizado com sucesso!"
```

---

## 🔄 Fluxo de Downgrade

```
1. Usuário clica em "Fazer Downgrade"
   ↓
2. JavaScript chama /planos/downgrade (AJAX)
   ↓
3. Controller valida:
   - Usuário logado?
   - Tem assinatura ativa?
   - Novo plano existe?
   - É realmente downgrade (preço menor)?
   ↓
4. Verifica limite de imóveis:
   - Conta imóveis ativos do usuário
   - Se > limite do novo plano:
     → Inativa TODOS os imóveis
     → Prepara mensagem de aviso
   ↓
5. Atualiza no Stripe:
   - Troca o price_id da assinatura
   - Stripe calcula proporcional
   - Credita diferença (usado no próximo pagamento)
   ↓
6. Atualiza no banco de dados:
   - Muda plan_id da subscription
   - Atualiza updated_at
   ↓
7. Retorna JSON success com mensagem
   ↓
8. JavaScript redireciona para /dashboard
   ↓
9. Usuário vê mensagem:
   "✅ Downgrade realizado com sucesso!
    Seus imóveis foram inativados.
    Acesse 'Meus Imóveis' e reative até X imóveis."
```

---

## 💰 Cálculo Proporcional (Stripe)

### **Como Funciona:**

O Stripe calcula automaticamente o valor proporcional quando você usa `proration_behavior: always_invoice`.

#### **Exemplo de Upgrade:**
```
Plano Atual: R$ 99,90/mês
Novo Plano: R$ 199,90/mês
Diferença: +R$ 100,00/mês

Situação:
- Usuário pagou R$ 99,90 no dia 01/10
- Faz upgrade no dia 15/10
- Faltam 16 dias para o próximo pagamento

Cálculo do Stripe:
1. Crédito do plano antigo (16 dias): R$ 53,28
2. Débito do novo plano (16 dias): R$ 106,56
3. Diferença a pagar: R$ 53,28

Resultado:
- Stripe cobra R$ 53,28 imediatamente
- Próximo pagamento: R$ 199,90 no dia 01/11
```

#### **Exemplo de Downgrade:**
```
Plano Atual: R$ 199,90/mês
Novo Plano: R$ 99,90/mês
Diferença: -R$ 100,00/mês

Situação:
- Usuário pagou R$ 199,90 no dia 01/10
- Faz downgrade no dia 15/10
- Faltam 16 dias para o próximo pagamento

Cálculo do Stripe:
1. Crédito do plano antigo (16 dias): R$ 106,56
2. Débito do novo plano (16 dias): R$ 53,28
3. Diferença a creditar: R$ 53,28

Resultado:
- Stripe credita R$ 53,28 na conta
- Crédito usado no próximo pagamento
- Próximo pagamento: R$ 46,62 (R$ 99,90 - R$ 53,28)
```

---

## 🎯 Regras de Negócio

### **Upgrade:**
1. ✅ Permitido a qualquer momento
2. ✅ Cobra diferença proporcional imediatamente
3. ✅ Não afeta imóveis cadastrados
4. ✅ Benefícios do novo plano aplicados imediatamente

### **Downgrade:**
1. ✅ Permitido a qualquer momento
2. ✅ Credita diferença proporcional (usado no próximo pagamento)
3. ⚠️ **Se imóveis > limite do novo plano:**
   - Inativa TODOS os imóveis
   - Usuário deve reativar manualmente até o limite
4. ✅ Limitações do novo plano aplicadas imediatamente

---

## 🚨 Tratamento de Imóveis no Downgrade

### **Cenário:**
```
Plano Atual: Premium (ilimitado)
Novo Plano: Básico (50 imóveis)
Imóveis Cadastrados: 100
```

### **O que acontece:**

1. **Sistema inativa TODOS os 100 imóveis**
2. **Mensagem exibida:**
   ```
   ✅ Downgrade realizado com sucesso!
   Seus imóveis foram inativados.
   Acesse 'Meus Imóveis' e reative até 50 imóveis.
   ```
3. **Usuário vai em "Meus Imóveis"**
4. **Vê lista de imóveis inativos**
5. **Escolhe quais 50 reativar**
6. **Sistema valida o limite ao reativar**

### **Implementação Futura (Sugestão):**

Criar página especial após downgrade:
```
┌────────────────────────────────────────┐
│ ⚠️ Seu plano foi alterado              │
│                                         │
│ Novo limite: 50 imóveis                │
│ Imóveis inativos: 100                  │
│                                         │
│ Escolha quais imóveis reativar:        │
│                                         │
│ [ ] Apartamento Centro - R$ 500k       │
│ [ ] Casa Praia - R$ 800k               │
│ [ ] Sala Comercial - R$ 300k           │
│ ...                                     │
│                                         │
│ Selecionados: 0/50                     │
│                                         │
│ [Reativar Selecionados]                │
└────────────────────────────────────────┘
```

---

## 🧪 Como Testar

### **Teste de Upgrade:**

1. Faça login como corretor
2. Acesse `/planos`
3. Veja seu plano atual (ex: Básico - R$ 49,90)
4. Veja plano superior (ex: Profissional - R$ 99,90)
5. Veja botão verde "Fazer Upgrade"
6. Veja texto "+R$ 50,00/mês"
7. Clique em "Fazer Upgrade"
8. Aguarde processamento
9. Veja mensagem de sucesso
10. Verifique no dashboard o novo plano

### **Teste de Downgrade:**

1. Faça login como corretor
2. Acesse `/planos`
3. Veja seu plano atual (ex: Premium - R$ 199,90)
4. Veja plano inferior (ex: Básico - R$ 49,90)
5. Veja botão amarelo "Fazer Downgrade"
6. Veja texto "Economize R$ 150,00/mês"
7. Clique em "Fazer Downgrade"
8. Aguarde processamento
9. Veja mensagem de sucesso (com aviso de imóveis se aplicável)
10. Verifique no dashboard o novo plano

### **Teste de Inativação de Imóveis:**

1. Cadastre 100 imóveis no plano Premium
2. Faça downgrade para Básico (50 imóveis)
3. Veja mensagem: "Seus imóveis foram inativados..."
4. Acesse "Meus Imóveis"
5. Veja todos os imóveis inativos
6. Tente reativar mais de 50
7. Sistema deve bloquear

---

## 📊 Validações Implementadas

### **No Controller:**

✅ Usuário está logado?  
✅ Tem assinatura ativa?  
✅ Plano existe e está ativo?  
✅ É realmente upgrade (preço maior)?  
✅ É realmente downgrade (preço menor)?  
✅ Plano tem stripe_price_id configurado?  

### **No Stripe:**

✅ Assinatura existe?  
✅ Price ID é válido?  
✅ Cálculo proporcional correto?  

### **No Banco de Dados:**

✅ Update da subscription bem-sucedido?  
✅ Imóveis inativados corretamente?  

---

## 🎨 Design e UX

### **Cores:**

| Elemento | Cor | Uso |
|----------|-----|-----|
| Upgrade | Verde (#10B981) | Botão e texto de diferença |
| Downgrade | Amarelo (#F59E0B) | Botão e texto de economia |
| Plano Atual | Cinza (#6B7280) | Botão desabilitado |

### **Ícones:**

- 🚀 Upgrade (seta para cima)
- ⬇️ Downgrade (seta para baixo)
- ✓ Plano Atual (check)

### **Animações:**

- Hover no botão: `scale-105` (upgrade)
- Loading: Spinner animado
- Transições: `transition-all duration-200`

---

## 🔒 Segurança

### **Validações:**

1. ✅ Verificação de login em todos os métodos
2. ✅ Validação de propriedade da assinatura (user_id)
3. ✅ Sanitização de inputs (plan_id)
4. ✅ Verificação de planos ativos
5. ✅ Try-catch em chamadas Stripe

### **Proteções:**

1. ✅ Não permite upgrade/downgrade sem assinatura
2. ✅ Não permite trocar para plano inativo
3. ✅ Não permite trocar para plano sem stripe_price_id
4. ✅ Retorna erros claros em JSON

---

## 📝 Próximos Passos (Futuro)

### **1. Modal de Confirmação**
- Mostrar resumo antes de confirmar
- Exibir cálculo proporcional
- Listar mudanças de recursos

### **2. Página de Reativação de Imóveis**
- Interface para escolher quais imóveis reativar
- Contador de selecionados vs limite
- Preview dos imóveis

### **3. Histórico de Mudanças de Plano**
- Tabela com todas as trocas
- Datas e valores
- Motivos (upgrade/downgrade)

### **4. Notificações**
- Email ao fazer upgrade/downgrade
- Aviso quando imóveis são inativados
- Lembrete para reativar imóveis

### **5. Comparação de Planos**
- Modal com tabela comparativa
- Destacar diferenças
- Botão direto para upgrade/downgrade

---

## ✅ Checklist de Implementação

- [x] Lógica de botões inteligentes na view
- [x] Função JavaScript `iniciarUpgrade()`
- [x] Função JavaScript `iniciarDowngrade()`
- [x] Método `upgrade()` no controller
- [x] Método `downgrade()` no controller
- [x] Método `update_subscription()` no Stripe_lib
- [x] Método `count_by_user()` no Imovel_model
- [x] Método `inativar_todos_by_user()` no Imovel_model
- [x] Validações de segurança
- [x] Tratamento de erros
- [x] Cálculo proporcional no Stripe
- [x] Inativação de imóveis no downgrade
- [x] Mensagens de sucesso/erro
- [x] Documentação completa
- [ ] Testes em ambiente de produção
- [ ] Modal de confirmação (futuro)
- [ ] Página de reativação de imóveis (futuro)

---

## 🎉 Resultado Final

### **Antes:**
❌ Usuário bloqueado, precisa cancelar assinatura  
❌ Perde acesso durante a troca  
❌ Processo manual e demorado  
❌ Sem cálculo proporcional  

### **Depois:**
✅ Upgrade/downgrade em 1 clique  
✅ Troca imediata, sem perder acesso  
✅ Stripe calcula proporcional automaticamente  
✅ Interface intuitiva e visual  
✅ Mensagens claras sobre mudanças  

---

**Sistema completo e funcional! 🚀**

Para dúvidas ou suporte: Rafael Dias - doisr.com.br
