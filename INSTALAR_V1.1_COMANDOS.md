# ⚡ Instalação Rápida v1.1 - Comandos

> **IMPORTANTE:** Execute os comandos na ordem!

---

## 📋 Pré-requisitos

- ✅ XAMPP rodando (Apache + MySQL)
- ✅ Banco `corretor_saas` existente
- ✅ Sistema v1.0 funcionando

---

## 🚀 Comandos de Instalação

### 1. Backup do Banco (OBRIGATÓRIO!)

```bash
# Via MySQL
mysqldump -u root -p corretor_saas > backup_antes_v1.1.sql
```

### 2. Aplicar Migration

```bash
# Navegar até a pasta
cd c:\xampp\htdocs\conectcorretores

# Aplicar migration
mysql -u root -p corretor_saas < database/migration_v1.1.sql
```

### 3. Verificar Instalação

```sql
-- Abrir MySQL
mysql -u root -p corretor_saas

-- Executar verificações
SELECT COUNT(*) as total_estados FROM estados;
-- Deve retornar: 27

SELECT COUNT(*) as total_cidades FROM cidades;
-- Deve retornar: 0 (popula via uso)

DESCRIBE imoveis;
-- Verificar se tem: cep, estado_id, cidade_id, link, whatsapp

exit;
```

### 4. Testar no Navegador

```
http://localhost/conectcorretores/imoveis/novo
```

---

## ✅ Checklist Rápido

- [ ] Backup criado
- [ ] Migration aplicada
- [ ] 27 estados no banco
- [ ] Formulário abre sem erro
- [ ] Busca de CEP funciona
- [ ] Estados carregam no select
- [ ] Cidades carregam ao selecionar estado
- [ ] Máscaras funcionam (R$, telefone)
- [ ] Valor/m² calcula automaticamente
- [ ] Consegue cadastrar imóvel

---

## 🔧 Comandos de Troubleshooting

### Limpar Cache

```bash
del c:\xampp\htdocs\conectcorretores\application\cache\*
```

### Verificar Logs

```bash
# Log do PHP
type c:\xampp\php\logs\php_error_log

# Log do Apache
type c:\xampp\apache\logs\error.log
```

### Reverter (Rollback)

```bash
# Restaurar backup
mysql -u root -p corretor_saas < backup_antes_v1.1.sql

# Restaurar formulário antigo
copy application\views\imoveis\form.php.backup application\views\imoveis\form.php
```

---

## 📞 Problemas Comuns

### "Table 'estados' doesn't exist"
```bash
mysql -u root -p corretor_saas < database/migration_v1.1.sql
```

### "Cannot find module Estado_model"
```bash
# Verificar se arquivo existe
dir application\models\Estado_model.php
```

### CEP não funciona
```
1. Verificar internet
2. Testar: https://viacep.com.br/ws/01310100/json/
3. Verificar console do navegador (F12)
```

---

✅ **Pronto! Sistema atualizado para v1.1**
