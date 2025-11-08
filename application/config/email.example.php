<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Configurações de Email - ConectCorretores
 *
 * Autor: Rafael Dias - doisr.com.br
 * Data: 06/11/2025
 */

// Protocolo de envio
$config['email_protocol'] = 'smtp';

// Configurações SMTP - BREVO (Sendinblue)
// Serviço profissional de envio de emails transacionais
$config['smtp_host'] = 'smtp-relay.brevo.com';
$config['smtp_port'] = 587;
$config['smtp_crypto'] = 'tls'; // TLS para porta 587

// Credenciais SMTP - BREVO
// ⚠️ CONFIGURAR: Obter credenciais em https://app.brevo.com/settings/keys/smtp
$config['smtp_user'] = ''; // seu-login@smtp-brevo.com
$config['smtp_pass'] = ''; // sua-chave-smtp-brevo

// Configurações de email
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['crlf'] = "\r\n";
$config['wordwrap'] = TRUE;

// Remetente padrão
$config['from_email'] = 'noreply@conectcorretores.com.br';
$config['from_name'] = 'ConectCorretores';

// URLs do sistema (para links nos emails)
$config['site_url'] = base_url();
$config['site_name'] = 'ConectCorretores';

// Configurações de desenvolvimento
$config['email_debug'] = ENVIRONMENT === 'development'; // TRUE em desenvolvimento
$config['email_log'] = TRUE; // Salvar log de emails enviados

/**
 * CONFIGURAÇÃO BREVO (Sendinblue):
 *
 * Servidor SMTP: smtp-relay.brevo.com
 * Porta: 587 (TLS)
 * Login: Obter em https://app.brevo.com/settings/keys/smtp
 * Senha: Chave SMTP gerada no painel Brevo
 * Autenticação: Obrigatória
 *
 * VANTAGENS DO BREVO:
 * ✅ Alta taxa de entrega (deliverability)
 * ✅ Não cai em spam
 * ✅ Estatísticas de envio
 * ✅ 300 emails/dia grátis
 * ✅ Suporte a templates
 *
 * PAINEL BREVO:
 * https://app.brevo.com/
 *
 * ALTERNATIVAS:
 * - Gmail: smtp.gmail.com (porta 587, TLS)
 * - SendGrid: smtp.sendgrid.net (porta 587)
 * - Mailgun: smtp.mailgun.org (porta 587)
 * - Amazon SES: email-smtp.us-east-1.amazonaws.com (porta 587)
 */
