<?php
/**
 * Layout Base para Configurações - Tabler
 * Baseado em: https://preview.tabler.io/settings.html
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 11/11/2025
 */
defined('BASEPATH') OR exit('No direct script access allowed');

// Preparar dados para o layout
$data_layout = [
    'title' => isset($page_title) ? $page_title : 'Configurações',
    'page' => 'settings',
    'page_header' => isset($page_title) ? $page_title : 'Configurações',
    'page_pretitle' => 'Admin'
];

// Iniciar conteúdo
ob_start();
?>

<div class="container-xl">
    <div class="row row-cards">
        <!-- Sidebar de Navegação -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Configurações</h4>
                    <div class="list-group list-group-transparent">
                        <a href="<?php echo base_url('settings/assinaturas'); ?>" 
                           class="list-group-item list-group-item-action <?php echo isset($active_tab) && $active_tab === 'assinaturas' ? 'active' : ''; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-credit-card me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 5m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M3 10l18 0" /><path d="M7 15l.01 0" /><path d="M11 15l2 0" /></svg>
                            Assinaturas
                        </a>
                        <a href="<?php echo base_url('settings/site'); ?>" 
                           class="list-group-item list-group-item-action <?php echo isset($active_tab) && $active_tab === 'site' ? 'active' : ''; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-world me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M3.6 9h16.8" /><path d="M3.6 15h16.8" /><path d="M11.5 3a17 17 0 0 0 0 18" /><path d="M12.5 3a17 17 0 0 1 0 18" /></svg>
                            Site
                        </a>
                        <a href="<?php echo base_url('settings/email'); ?>" 
                           class="list-group-item list-group-item-action <?php echo isset($active_tab) && $active_tab === 'email' ? 'active' : ''; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mail me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
                            Email
                        </a>
                        <a href="<?php echo base_url('settings/seguranca'); ?>" 
                           class="list-group-item list-group-item-action <?php echo isset($active_tab) && $active_tab === 'seguranca' ? 'active' : ''; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shield me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" /></svg>
                            Segurança
                        </a>
                        <a href="<?php echo base_url('settings/imoveis'); ?>" 
                           class="list-group-item list-group-item-action <?php echo isset($active_tab) && $active_tab === 'imoveis' ? 'active' : ''; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                            Imóveis
                        </a>
                        <a href="<?php echo base_url('settings/sistema'); ?>" 
                           class="list-group-item list-group-item-action <?php echo isset($active_tab) && $active_tab === 'sistema' ? 'active' : ''; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
                            Sistema
                        </a>
                        <a href="<?php echo base_url('settings/cupons'); ?>" 
                           class="list-group-item list-group-item-action <?php echo isset($active_tab) && $active_tab === 'cupons' ? 'active' : ''; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-ticket me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 5l0 2" /><path d="M15 11l0 2" /><path d="M15 17l0 2" /><path d="M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-3a2 2 0 0 0 0 -4v-3a2 2 0 0 1 2 -2" /></svg>
                            Cupons
                        </a>
                        <a href="<?php echo base_url('settings/todas'); ?>" 
                           class="list-group-item list-group-item-action <?php echo isset($active_tab) && $active_tab === 'todas' ? 'active' : ''; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-list me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l11 0" /><path d="M9 12l11 0" /><path d="M9 18l11 0" /><path d="M5 6l0 .01" /><path d="M5 12l0 .01" /><path d="M5 18l0 .01" /></svg>
                            Todas
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Conteúdo Principal -->
        <div class="col-md-9">
            <!-- Content Area -->
            <?php echo isset($content) ? $content : ''; ?>
        </div>
    </div>
</div>

<?php
// Capturar conteúdo
$data_layout['content'] = ob_get_clean();

// Carregar layout
$this->load->view('templates/tabler/layout', $data_layout);
?>
