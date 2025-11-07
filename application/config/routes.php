<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
/**
 * Rotas do Sistema - ConectCorretores
 * Autor: Rafael Dias - doisr.com.br
 */

// Rota padrão
$route['default_controller'] = 'home';
$route['404_override'] = 'errors/page_404';
$route['translate_uri_dashes'] = TRUE;

// ========================================
// Rotas de Autenticação
// ========================================
$route['login'] = 'auth/login';
$route['register'] = 'auth/register';
$route['cadastro'] = 'auth/register';
$route['logout'] = 'auth/logout';
$route['esqueci-senha'] = 'auth/forgot_password';
$route['redefinir-senha/(:any)'] = 'auth/reset_password/$1';

// ========================================
// Rotas do Dashboard
// ========================================
$route['dashboard'] = 'dashboard/index';
$route['perfil'] = 'dashboard/perfil';
$route['perfil/editar'] = 'dashboard/editar_perfil';

// ========================================
// Rotas de Imóveis
// ========================================
$route['imoveis'] = 'imoveis/index';
$route['imoveis/novo'] = 'imoveis/novo';
$route['imoveis/ver/(:num)'] = 'imoveis/ver/$1';
$route['imoveis/editar/(:num)'] = 'imoveis/editar/$1';
$route['imoveis/deletar/(:num)'] = 'imoveis/deletar/$1';
$route['imoveis/toggle-status/(:num)'] = 'imoveis/toggle_status/$1';
$route['imoveis/toggle-destaque/(:num)'] = 'imoveis/toggle_destaque/$1';

// ========================================
// Rotas de Planos e Assinaturas
// ========================================
$route['planos'] = 'planos/index';
$route['planos/portal'] = 'planos/portal';
$route['planos/escolher/(:num)'] = 'planos/escolher/$1';
$route['planos/cancelar'] = 'planos/cancelar';
$route['checkout/(:num)'] = 'checkout/index/$1';
$route['checkout/processar'] = 'checkout/process';
$route['checkout/sucesso'] = 'checkout/success';
$route['checkout/cancelado'] = 'checkout/cancel';
$route['webhook/stripe'] = 'webhook/stripe';

// ========================================
// Rotas do Admin
// ========================================
$route['admin'] = 'admin/dashboard';
$route['admin/dashboard'] = 'admin/dashboard';
$route['admin/usuarios'] = 'admin/usuarios';
$route['admin/usuarios/editar/(:num)'] = 'admin/editar_usuario/$1';
$route['admin/assinaturas'] = 'admin/assinaturas';
$route['admin/planos'] = 'admin/planos';
$route['admin/relatorios'] = 'admin/relatorios';
