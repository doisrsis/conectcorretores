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

// Configurações SMTP - ValueServer
$config['smtp_host'] = 'br61-cp.valueserver.com.br';
$config['smtp_port'] = 465;
$config['smtp_crypto'] = 'ssl'; // SSL para porta 465

// Credenciais SMTP
$config['smtp_user'] = 'noreply@conectcorretores.com.br';
$config['smtp_pass'] = 'U248nKFUVgksm[&O@2025';

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
 * CONFIGURAÇÃO VALUESERVER:
 *
 * Servidor SMTP: br61-cp.valueserver.com.br
 * Porta: 465 (SSL/TLS)
 * Usuário: noreply@conectcorretores.com.br
 * Autenticação: Obrigatória
 *
 * ALTERNATIVAS:
 * - Gmail: smtp.gmail.com (porta 587, TLS)
 * - SendGrid: smtp.sendgrid.net (porta 587)
 * - Mailgun: smtp.mailgun.org (porta 587)
 * - Amazon SES: email-smtp.us-east-1.amazonaws.com (porta 587)
 */
