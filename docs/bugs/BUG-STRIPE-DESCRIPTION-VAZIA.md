# 🐛 Bug: Erro ao Criar Plano com Description Vazia

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 06/11/2025  
**Status:** ✅ Resolvido

---

## 🐛 Descrição do Bug

Ao tentar criar um novo plano pelo dashboard administrativo, o sistema retornava erro:

```
Erro ao criar produto no Stripe: You passed an empty string for 'description'. 
We assume empty values are an attempt to unset a parameter; however 'description' 
cannot be unset. You should remove 'description' from your request or supply a 
non-empty value.
```

---

## 🔍 Causa Raiz

A biblioteca `Stripe_lib.php` estava enviando o campo `description` com string vazia para a API do Stripe ao criar produtos.

O Stripe não aceita campos com string vazia - ou o campo deve ser omitido, ou deve conter um valor válido.

### **Código Problemático:**

```php
public function create_product($name, $description = null) {
    $product = \Stripe\Product::create([
        'name' => $name,
        'description' => $description, // ❌ Enviando null ou ''
    ]);
}
```

---

## ✅ Solução Implementada

### **1. Método `create_product()`**

Modificado para adicionar `description` apenas se não estiver vazia:

```php
public function create_product($name, $description = null) {
    // Preparar dados do produto
    $product_data = ['name' => $name];
    
    // Adicionar descrição apenas se não estiver vazia
    if (!empty($description)) {
        $product_data['description'] = $description;
    }
    
    $product = \Stripe\Product::create($product_data);
}
```

### **2. Método `update_product()`**

Modificado para filtrar todos os campos vazios:

```php
public function update_product($product_id, $data) {
    // Filtrar campos vazios para evitar erro do Stripe
    $filtered_data = [];
    foreach ($data as $key => $value) {
        if ($value !== '' && $value !== null) {
            $filtered_data[$key] = $value;
        }
    }
    
    $product = \Stripe\Product::update($product_id, $filtered_data);
}
```

---

## 📝 Arquivos Modificados

```
application/libraries/Stripe_lib.php
├─ create_product() - Linha 212-229
└─ update_product() - Linha 253-269
```

---

## 🧪 Como Testar

### **1. Criar Plano Sem Descrição:**

1. Login como admin
2. Acessar: Gerenciar Planos
3. Criar novo plano
4. Deixar descrição vazia
5. Salvar

**Resultado Esperado:** ✅ Plano criado com sucesso no Stripe

### **2. Criar Plano Com Descrição:**

1. Criar novo plano
2. Preencher descrição
3. Salvar

**Resultado Esperado:** ✅ Plano criado com descrição no Stripe

### **3. Atualizar Plano:**

1. Editar plano existente
2. Remover descrição (deixar vazio)
3. Salvar

**Resultado Esperado:** ✅ Plano atualizado sem erro

---

## 🎯 Contexto Adicional

### **Mudança de Ambiente:**

O sistema estava usando chaves de API do ambiente **restrito** (live) e foi migrado para ambiente de **teste**.

**Chaves Atualizadas:**
```
Ambiente: test
Public Key: pk_test_51SJCoQ0CRJ9ato0i...
Secret Key: sk_test_51SJCoQ0CRJ9ato0i...
```

Após a mudança, os produtos precisaram ser recriados no ambiente de teste, revelando o bug.

---

## 📊 Impacto

### **Antes da Correção:**
- ❌ Impossível criar planos pelo dashboard
- ❌ Impossível atualizar planos com descrição vazia
- ❌ Bloqueio na gestão de produtos

### **Depois da Correção:**
- ✅ Criação de planos funcionando
- ✅ Atualização de planos funcionando
- ✅ Campos opcionais tratados corretamente

---

## 🔄 Prevenção

### **Boas Práticas Implementadas:**

1. **Validação de Campos Vazios:**
   - Filtrar campos antes de enviar para API
   - Usar `!empty()` para verificar valores

2. **Tratamento de Campos Opcionais:**
   - Não enviar campos opcionais vazios
   - Construir array dinamicamente

3. **Documentação da API:**
   - Seguir especificação do Stripe
   - Campos opcionais devem ser omitidos se vazios

---

## 📚 Referências

- [Stripe Products API](https://stripe.com/docs/api/products)
- [Stripe Error Handling](https://stripe.com/docs/error-handling)
- [Best Practices - Optional Parameters](https://stripe.com/docs/api/metadata)

---

## ✅ Verificação Final

- [x] Bug identificado
- [x] Causa raiz encontrada
- [x] Solução implementada
- [x] Código testado
- [x] Documentação criada
- [x] Prevenção implementada

---

**Bug resolvido! Sistema de criação de planos funcionando corretamente. ✅**

Para suporte: Rafael Dias - doisr.com.br
