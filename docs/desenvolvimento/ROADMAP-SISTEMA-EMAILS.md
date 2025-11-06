# 📧 Roadmap - Sistema de Emails Transacionais

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 06/11/2025  
**Prioridade:** 🔴 ALTA  
**Tempo Estimado:** 1 semana

---

## 🎯 Objetivo

Implementar sistema completo de emails transacionais para melhorar comunicação com usuários e profissionalizar o sistema.

---

## 📋 Emails a Implementar

### **1. Bem-vindo (Após Cadastro)**
- Assunto: "Bem-vindo ao ConectCorretores!"
- Conteúdo: Boas-vindas, próximos passos, link para escolher plano

### **2. Confirmação de Assinatura**
- Assunto: "Assinatura Ativada - ConectCorretores"
- Conteúdo: Confirmação do plano, benefícios, data de renovação

### **3. Pagamento Confirmado**
- Assunto: "Pagamento Recebido - ConectCorretores"
- Conteúdo: Valor pago, método, próxima cobrança

### **4. Renovação Próxima (7 dias antes)**
- Assunto: "Seu plano renova em 7 dias"
- Conteúdo: Data de renovação, valor, link para gerenciar

### **5. Falha no Pagamento**
- Assunto: "Problema com seu pagamento - Ação Necessária"
- Conteúdo: Falha detectada, instruções para atualizar cartão

### **6. Plano Vencido**
- Assunto: "Seu plano expirou - ConectCorretores"
- Conteúdo: Plano vencido, imóveis desativados, link para renovar

### **7. Upgrade Confirmado**
- Assunto: "Upgrade Realizado com Sucesso!"
- Conteúdo: Novo plano, novos benefícios, valor ajustado

### **8. Downgrade Confirmado**
- Assunto: "Plano Alterado - ConectCorretores"
- Conteúdo: Novo plano, limite de imóveis, próxima cobrança

### **9. Cancelamento Confirmado**
- Assunto: "Assinatura Cancelada - ConectCorretores"
- Conteúdo: Confirmação, data de término, feedback

### **10. Recuperação de Senha**
- Assunto: "Redefinir sua senha - ConectCorretores"
- Conteúdo: Link para redefinir, validade do link

---

## 🛠️ Tecnologias a Usar

### **Opção A: PHPMailer (Recomendado para início)**
```
Prós:
✅ Gratuito
✅ Fácil de configurar
✅ Funciona com SMTP
✅ Sem limite de envios (depende do servidor)

Contras:
❌ Requer configuração de SMTP
❌ Pode cair em spam sem configuração adequada
❌ Sem analytics
```

### **Opção B: SendGrid (Recomendado para produção)**
```
Prós:
✅ 100 emails/dia grátis
✅ Alta entregabilidade
✅ Analytics e tracking
✅ Templates prontos
✅ API simples

Contras:
❌ Requer cadastro
❌ Limite no plano gratuito
❌ Custo após limite
```

### **Opção C: Mailgun**
```
Prós:
✅ 5.000 emails/mês grátis (3 meses)
✅ Boa entregabilidade
✅ API robusta

Contras:
❌ Requer cartão de crédito
❌ Configuração mais complexa
```

**Recomendação:** Começar com PHPMailer, migrar para SendGrid em produção.

---

## 📁 Estrutura de Arquivos

```
application/
├── libraries/
│   └── Email_lib.php              # Biblioteca de emails
├── config/
│   └── email.php                  # Configurações de email
├── views/
│   └── emails/
│       ├── layout.php             # Layout base
│       ├── welcome.php            # Bem-vindo
│       ├── subscription_activated.php
│       ├── payment_confirmed.php
│       ├── renewal_reminder.php
│       ├── payment_failed.php
│       ├── plan_expired.php
│       ├── upgrade_confirmed.php
│       ├── downgrade_confirmed.php
│       ├── cancellation_confirmed.php
│       └── password_reset.php
└── helpers/
    └── email_helper.php           # Funções auxiliares
```

---

## 🚀 Plano de Implementação

### **Fase 1: Setup Básico (Dia 1)**

#### **1.1. Instalar PHPMailer**
```bash
composer require phpmailer/phpmailer
```

#### **1.2. Criar Configuração**
```php
// application/config/email.php
$config['email_protocol'] = 'smtp';
$config['smtp_host'] = 'smtp.gmail.com';
$config['smtp_port'] = 587;
$config['smtp_user'] = 'seu-email@gmail.com';
$config['smtp_pass'] = 'sua-senha-app';
$config['smtp_crypto'] = 'tls';
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['from_email'] = 'noreply@conectcorretores.com.br';
$config['from_name'] = 'ConectCorretores';
```

#### **1.3. Criar Library**
```php
// application/libraries/Email_lib.php
class Email_lib {
    private $CI;
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->config('email');
    }
    
    public function send($to, $subject, $template, $data = []) {
        // Implementação
    }
}
```

---

### **Fase 2: Templates (Dia 2-3)**

#### **2.1. Criar Layout Base**
```html
<!-- application/views/emails/layout.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        /* Estilos inline para compatibilidade */
    </style>
</head>
<body>
    <div class="container">
        <header>
            <img src="<?php echo base_url('assets/img/logo.png'); ?>" alt="ConectCorretores">
        </header>
        <main>
            <?php echo $content; ?>
        </main>
        <footer>
            <p>&copy; 2025 ConectCorretores. Todos os direitos reservados.</p>
        </footer>
    </div>
</body>
</html>
```

#### **2.2. Criar Templates Individuais**
Um template para cada tipo de email.

---

### **Fase 3: Integração (Dia 4-5)**

#### **3.1. Integrar com Cadastro**
```php
// application/controllers/Auth.php
// Após criar usuário:
$this->load->library('email_lib');
$this->email_lib->send(
    $user->email,
    'Bem-vindo ao ConectCorretores!',
    'welcome',
    ['nome' => $user->nome]
);
```

#### **3.2. Integrar com Webhooks**
```php
// application/controllers/Planos.php
// Após confirmar pagamento:
$this->email_lib->send(
    $user->email,
    'Pagamento Confirmado',
    'payment_confirmed',
    ['plano' => $plan->nome, 'valor' => $plan->preco]
);
```

---

### **Fase 4: Testes (Dia 6)**

#### **4.1. Testes Unitários**
- Testar envio de cada tipo de email
- Verificar formatação
- Testar com diferentes dados

#### **4.2. Testes de Entregabilidade**
- Verificar se chega na caixa de entrada
- Verificar se não cai em spam
- Testar em diferentes provedores (Gmail, Outlook, etc.)

---

### **Fase 5: Documentação (Dia 7)**

#### **5.1. Documentar Uso**
- Como adicionar novo tipo de email
- Como personalizar templates
- Como configurar SMTP

#### **5.2. Troubleshooting**
- Problemas comuns
- Soluções

---

## 📧 Exemplo de Implementação

### **Email de Boas-Vindas**

```php
// application/views/emails/welcome.php
<h1>Bem-vindo ao ConectCorretores, <?php echo $nome; ?>!</h1>

<p>Estamos muito felizes em ter você conosco!</p>

<p>O ConectCorretores é a plataforma ideal para corretores de imóveis gerenciarem seus anúncios de forma profissional.</p>

<h2>Próximos Passos:</h2>
<ol>
    <li>Escolha seu plano</li>
    <li>Cadastre seus primeiros imóveis</li>
    <li>Comece a divulgar</li>
</ol>

<a href="<?php echo base_url('planos'); ?>" class="button">Escolher Plano</a>

<p>Se tiver dúvidas, estamos à disposição!</p>

<p>Atenciosamente,<br>Equipe ConectCorretores</p>
```

---

## ⚙️ Configuração SMTP

### **Gmail (Para Testes)**
```
Host: smtp.gmail.com
Port: 587
Security: TLS
User: seu-email@gmail.com
Pass: senha-de-app (não a senha normal)
```

**Como gerar senha de app:**
1. Conta Google > Segurança
2. Verificação em duas etapas (ativar)
3. Senhas de app
4. Gerar senha para "Email"

### **SendGrid (Para Produção)**
```
Host: smtp.sendgrid.net
Port: 587
Security: TLS
User: apikey
Pass: SUA_API_KEY_AQUI
```

---

## ✅ Checklist de Implementação

### **Setup**
- [ ] PHPMailer instalado
- [ ] Configuração de email criada
- [ ] Library Email_lib criada
- [ ] SMTP configurado e testado

### **Templates**
- [ ] Layout base criado
- [ ] Template de boas-vindas
- [ ] Template de assinatura ativada
- [ ] Template de pagamento confirmado
- [ ] Template de renovação próxima
- [ ] Template de falha de pagamento
- [ ] Template de plano vencido
- [ ] Template de upgrade
- [ ] Template de downgrade
- [ ] Template de cancelamento
- [ ] Template de recuperação de senha

### **Integração**
- [ ] Integrado com cadastro
- [ ] Integrado com webhooks
- [ ] Integrado com upgrade/downgrade
- [ ] Integrado com cancelamento
- [ ] Integrado com recuperação de senha

### **Testes**
- [ ] Todos os emails testados
- [ ] Entregabilidade verificada
- [ ] Formatação em diferentes clientes
- [ ] Links funcionando

### **Documentação**
- [ ] Guia de uso criado
- [ ] Troubleshooting documentado
- [ ] Exemplos de código

---

## 🎨 Design dos Emails

### **Princípios**
- ✅ Responsivo (mobile-first)
- ✅ Cores da marca
- ✅ Call-to-action claro
- ✅ Texto conciso
- ✅ Imagens otimizadas

### **Estrutura Padrão**
```
1. Logo
2. Título principal
3. Mensagem
4. Call-to-action (botão)
5. Informações adicionais
6. Footer com links
```

---

## 📊 Métricas a Acompanhar

- Taxa de entrega
- Taxa de abertura
- Taxa de cliques
- Taxa de spam
- Bounces (devoluções)

---

## 🚨 Considerações Importantes

### **Segurança**
- Nunca enviar senhas por email
- Usar tokens com expiração
- Validar endereços de email

### **Performance**
- Enviar emails de forma assíncrona
- Usar fila para grandes volumes
- Não bloquear requisições

### **Compliance**
- Incluir link de descadastro
- Respeitar LGPD
- Política de privacidade

---

## 📚 Recursos Úteis

- [PHPMailer Documentation](https://github.com/PHPMailer/PHPMailer)
- [SendGrid Documentation](https://docs.sendgrid.com/)
- [Email Design Best Practices](https://www.campaignmonitor.com/best-practices/)
- [HTML Email Templates](https://htmlemail.io/)

---

**Sistema de emails é essencial para profissionalizar a comunicação! 📧**

Para suporte: Rafael Dias - doisr.com.br
