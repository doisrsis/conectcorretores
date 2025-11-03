# 🐛 Bug: Select UF Não Listava Estados e Máscara de Preço

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 03/11/2025  
**Status:** ✅ Resolvido

---

## 📋 Descrição do Problema

### **Problema 1: Select UF Vazio**
O campo select de UF (Estado) no formulário de cadastro de imóveis não estava listando os estados disponíveis.

### **Problema 2: Máscara de Preço**
O campo "Valor m²" calculado automaticamente estava potencialmente interferindo com a máscara do campo "Preço".

---

## 🔍 Causa Raiz

### **Problema 1:**
O JavaScript estava tentando aplicar máscaras IMask em campos `telefone` e `whatsapp` que foram removidos do formulário nas melhorias recentes.

```javascript
// ❌ ERRO: Campos não existem mais
const telefoneMask = IMask(document.getElementById('telefone'), {...});
const whatsappMask = IMask(document.getElementById('whatsapp'), {...});
```

Quando `document.getElementById()` retornava `null`, o IMask lançava erro e **interrompia a execução do resto do script**, incluindo:
- Função `carregarEstados()`
- Máscaras de outros campos
- Validações dinâmicas

### **Problema 2:**
O campo "Valor m²" tinha listeners que recalculavam o valor a cada digitação no campo "Preço", potencialmente causando conflitos com a máscara IMask.

---

## ✅ Solução Implementada

### **1. Remover Máscaras de Campos Inexistentes**

**Arquivo:** `assets/js/imoveis-form.js`

```javascript
// ❌ REMOVIDO:
const telefoneMask = IMask(document.getElementById('telefone'), {...});
const whatsappMask = IMask(document.getElementById('whatsapp'), {...});
```

### **2. Remover Campo "Valor m²" do Formulário**

**Arquivo:** `application/views/imoveis/form.php`

```html
<!-- ❌ REMOVIDO: -->
<div>
    <label>Valor m² (calculado)</label>
    <input type="text" id="valor_m2" readonly>
</div>
```

**Alteração no Grid:**
- Antes: `grid-cols-3` (Preço, Área, Valor m²)
- Depois: `grid-cols-2` (Preço, Área)

### **3. Remover Cálculo Automático do JavaScript**

**Arquivo:** `assets/js/imoveis-form.js`

```javascript
// ❌ REMOVIDO:
function calcularValorM2() { ... }
precoInput.addEventListener('input', calcularValorM2);
areaInput.addEventListener('input', calcularValorM2);
```

### **4. Revisar Máscara de Preço - Centavos Sempre Visíveis**

**Arquivo:** `assets/js/imoveis-form.js`

**Problema adicional:** Os centavos (,00) só apareciam após o campo perder o foco, não durante a digitação.

**Solução:** Adicionar listeners customizados para forçar exibição dos centavos:

```javascript
// ✅ MELHORADO:
const precoMask = IMask(precoInput, {
    mask: 'R$ num',
    lazy: false,
    blocks: {
        num: {
            mask: Number,
            scale: 2,
            signed: false,
            thousandsSeparator: '.',
            radix: ',',
            mapToRadix: ['.'],
            padFractionalZeros: false, // Desabilitar padrão
            normalizeZeros: true,
            min: 0,
            max: 999999999.99
        }
    }
});

// Adicionar centavos manualmente durante digitação
precoInput.addEventListener('input', function(e) {
    const value = precoMask.value;
    if (value && value !== 'R$ ' && !value.includes(',')) {
        precoMask.value = value + ',00';
    }
});

// Garantir centavos ao perder foco
precoInput.addEventListener('blur', function() {
    const value = precoMask.value;
    if (value && value !== 'R$ ') {
        const parts = value.split(',');
        if (parts.length === 1) {
            precoMask.value = value + ',00';
        } else if (parts[1].length === 1) {
            precoMask.value = value + '0';
        }
    }
});
```

---

## 📊 Impacto

### **Antes:**
- ❌ Select UF vazio
- ❌ Impossível cadastrar imóveis
- ❌ JavaScript quebrado
- ⚠️ Campo "Valor m²" potencialmente interferindo
- ⚠️ Centavos só visíveis após perder foco

### **Depois:**
- ✅ Select UF lista todos os estados
- ✅ Cadastro funcionando normalmente
- ✅ JavaScript executando sem erros
- ✅ Máscara de preço otimizada
- ✅ Centavos sempre visíveis durante digitação
- ✅ Formulário mais limpo (2 campos ao invés de 3)

---

## 🧪 Como Testar

### **1. Testar Select UF**
```
1. Acessar /imoveis/novo
2. Campo UF deve listar todos os estados
3. Ao selecionar estado, campo Cidade deve habilitar
```

### **2. Testar Máscara de Preço**
```
1. Digitar no campo Preço: 1000
2. Deve aparecer: R$ 1.000,00 (com centavos durante digitação)
3. Digitar: 150000
4. Deve aparecer: R$ 150.000,00
5. Centavos devem estar sempre visíveis
6. Não deve haver travamentos ou erros
```

### **3. Testar Área Privativa**
```
1. Digitar no campo Área
2. Deve aceitar apenas números inteiros
3. Separador de milhares: 1.000
```

### **4. Verificar Console**
```
1. Abrir DevTools (F12)
2. Aba Console
3. Não deve haver erros JavaScript
```

---

## 📁 Arquivos Modificados

1. ✅ `assets/js/imoveis-form.js`
   - Removidas máscaras de telefone/whatsapp
   - Removido cálculo de valor m²
   - Melhorada máscara de preço

2. ✅ `application/views/imoveis/form.php`
   - Removido campo "Valor m²"
   - Grid alterado de 3 para 2 colunas

---

## 💡 Lições Aprendidas

1. **Sempre verificar dependências:** Ao remover campos do HTML, verificar se há JavaScript dependente
2. **Validar elementos antes de aplicar máscaras:** Usar verificações como `if (element) { ... }`
3. **Evitar cálculos em tempo real:** Podem interferir com máscaras e validações
4. **Manter formulários simples:** Menos campos = menos complexidade

---

## 🔄 Melhorias Relacionadas

Este bug foi descoberto durante a implementação das melhorias no cadastro de imóveis:
- Remoção de campos de contato (telefone, whatsapp, link)
- Adição de campo "Link do Imóvel"
- Simplificação do formulário

Ver: `docs/desenvolvimento/MELHORIAS-CADASTRO-IMOVEIS.md`

---

## ✅ Verificação Final

- [x] Select UF lista estados
- [x] Select Cidade funciona ao selecionar estado
- [x] Máscara de preço funciona corretamente
- [x] Máscara de área funciona corretamente
- [x] Busca de CEP funciona
- [x] Sem erros no console JavaScript
- [x] Formulário responsivo (2 colunas)
- [x] Documentação atualizada

---

**Bug resolvido com sucesso! 🎉**

Para suporte: Rafael Dias - doisr.com.br
