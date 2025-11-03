# 🧪 Teste Rápido - ConectCorretores

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 18/10/2025

---

## ✅ Correção Aplicada

Ajustei o controller `Imoveis.php` para garantir compatibilidade com a view.

---

## 🧪 Teste Agora:

### 1. Faça Login
```
http://localhost/conectcorretores/login
```

**Credenciais:**
- Email: admin@conectcorretores.com
- Senha: password

### 2. Acesse Cadastrar Imóvel
```
http://localhost/conectcorretores/imoveis/novo
```

### 3. Preencha o Formulário

**Dados de Teste:**
- Tipo de Negócio: Venda
- Tipo de Imóvel: Apartamento
- Preço: 250000
- Endereço: Rua das Flores
- Número: 123
- Bairro: Centro
- Cidade: São Paulo
- Estado: SP
- CEP: 01234-567
- Área Privativa: 85
- Quartos: 3
- Banheiros: 2
- Vagas: 2

### 4. Clique em "Cadastrar Imóvel"

---

## 🔍 Se Ainda Não Funcionar

### Verifique o erro exato:

1. **Abra o navegador em modo desenvolvedor** (F12)
2. **Vá na aba "Console"**
3. **Acesse a URL:**
   ```
   http://localhost/conectcorretores/imoveis/novo
   ```
4. **Veja se há algum erro JavaScript ou PHP**

### Ou verifique o log do PHP:

1. Abra: `C:\xampp\apache\logs\error.log`
2. Procure por erros recentes
3. Me informe qual é o erro exato

---

## 📋 Checklist de Verificação

- [ ] Apache está rodando
- [ ] MySQL está rodando  
- [ ] Você está logado no sistema
- [ ] A URL é exatamente: `http://localhost/conectcorretores/imoveis/novo`
- [ ] Não há erros no console do navegador

---

## 🎯 URLs para Testar

### Funcionando:
```
✅ http://localhost/conectcorretores
✅ http://localhost/conectcorretores/login
✅ http://localhost/conectcorretores/dashboard
✅ http://localhost/conectcorretores/imoveis
✅ http://localhost/conectcorretores/perfil
✅ http://localhost/conectcorretores/planos
✅ http://localhost/conectcorretores/admin/dashboard
✅ http://localhost/conectcorretores/admin/usuarios
✅ http://localhost/conectcorretores/admin/assinaturas
```

### Testando Agora:
```
🧪 http://localhost/conectcorretores/imoveis/novo
```

---

## 💡 Possíveis Problemas

### 1. Erro 404
**Causa:** Rota não encontrada  
**Solução:** Verifique se o `.htaccess` está correto

### 2. Página em Branco
**Causa:** Erro PHP  
**Solução:** Ative o display_errors no `php.ini`

### 3. Erro de Banco
**Causa:** Tabela não existe  
**Solução:** Importe o `database/schema.sql`

### 4. Erro de Sessão
**Causa:** Não está logado  
**Solução:** Faça login primeiro

---

## 🚀 Teste e Me Diga o Resultado!

**Qual é o erro exato que você está vendo?**

- [ ] Página 404
- [ ] Página em branco
- [ ] Erro de banco de dados
- [ ] Erro de PHP
- [ ] Outro (especifique)

---

**Me informe o erro exato para eu corrigir! 🎯**
