# 📊 Como Importar o Banco de Dados

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 18/10/2025

---

## ⚠️ ERRO ATUAL

```
Unknown column 'ativo' in 'field list'
```

**Causa:** Banco de dados não foi importado ainda!

---

## ✅ SOLUÇÃO 1: Via Script (MAIS FÁCIL)

### Execute o arquivo:

```
IMPORTAR_BANCO.bat
```

**O que ele faz:**
1. Conecta no MySQL
2. Importa o arquivo `database/schema.sql`
3. Cria todas as tabelas
4. Insere dados iniciais

---

## ✅ SOLUÇÃO 2: Via phpMyAdmin (VISUAL)

### Passo 1: Abrir phpMyAdmin

```
http://localhost/phpmyadmin
```

### Passo 2: Criar Banco de Dados

1. Clique em **"Novo"** (ou "New") no menu lateral
2. Nome do banco: `corretor_saas`
3. Collation: `utf8mb4_unicode_ci`
4. Clique em **"Criar"**

### Passo 3: Importar Schema

1. Clique no banco `corretor_saas` (menu lateral)
2. Clique na aba **"Importar"** (ou "Import")
3. Clique em **"Escolher arquivo"**
4. Selecione: `C:\xampp\htdocs\conectcorretores\database\schema.sql`
5. Clique em **"Executar"** (ou "Go")

### Passo 4: Verificar

Você deve ver 4 tabelas criadas:
- ✅ `users`
- ✅ `plans`
- ✅ `subscriptions`
- ✅ `imoveis`

---

## ✅ SOLUÇÃO 3: Via MySQL CLI

### Abra o CMD e execute:

```bash
cd C:\xampp\htdocs\conectcorretores

C:\xampp\mysql\bin\mysql.exe -u root -p < database\schema.sql
```

**Digite a senha do MySQL quando solicitado** (geralmente vazio, só pressione Enter)

---

## 🧪 Verificar se Importou Corretamente

### Via phpMyAdmin:

1. Acesse: `http://localhost/phpmyadmin`
2. Clique em `corretor_saas`
3. Clique na tabela `users`
4. Clique em **"Estrutura"**
5. Verifique se a coluna `ativo` existe

### Via SQL:

Execute no phpMyAdmin (aba SQL):

```sql
DESCRIBE users;
```

Deve mostrar a coluna `ativo` tipo `tinyint(1)`

---

## 📋 Dados Criados Automaticamente

Após importar, você terá:

### **1 Usuário Admin:**
```
Email: admin@conectcorretores.com
Senha: password
```

### **3 Planos:**
- Básico (R$ 49,90/mês)
- Profissional (R$ 99,90/mês)
- Premium (R$ 199,90/mês)

---

## 🔍 Troubleshooting

### Erro: "Access denied for user 'root'"

**Solução:** Verifique a senha do MySQL em `application/config/database.php`

### Erro: "Can't connect to MySQL server"

**Solução:** Inicie o MySQL no XAMPP Control Panel

### Erro: "Database already exists"

**Solução:** 
1. Delete o banco antigo no phpMyAdmin
2. Ou use este comando SQL:
   ```sql
   DROP DATABASE IF EXISTS corretor_saas;
   ```
3. Importe novamente

---

## ✅ Checklist

- [ ] MySQL rodando no XAMPP
- [ ] Banco `corretor_saas` criado
- [ ] Arquivo `schema.sql` importado
- [ ] 4 tabelas criadas
- [ ] Coluna `ativo` existe em `users`
- [ ] Usuário admin criado

---

## 🚀 Após Importar

**Teste o registro novamente:**

```
http://localhost/conectcorretores/register
```

Agora deve funcionar perfeitamente! ✅

---

**Escolha uma das 3 soluções acima e importe o banco agora! 🎯**
