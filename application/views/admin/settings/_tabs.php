<?php
/**
 * Partial: Tabs de Navegação das Configurações
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 08/11/2025
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="border-b border-gray-200">
    <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
        <a href="<?php echo base_url('settings/assinaturas'); ?>" 
           class="<?php echo $active_tab === 'assinaturas' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
            💳 Assinaturas
        </a>
        <a href="<?php echo base_url('settings/site'); ?>" 
           class="<?php echo $active_tab === 'site' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
            🌐 Site
        </a>
        <a href="<?php echo base_url('settings/email'); ?>" 
           class="<?php echo $active_tab === 'email' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
            📧 Email
        </a>
        <a href="<?php echo base_url('settings/seguranca'); ?>" 
           class="<?php echo $active_tab === 'seguranca' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
            🛡️ Segurança
        </a>
        <a href="<?php echo base_url('settings/imoveis'); ?>" 
           class="<?php echo $active_tab === 'imoveis' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
            🏠 Imóveis
        </a>
        <a href="<?php echo base_url('settings/sistema'); ?>" 
           class="<?php echo $active_tab === 'sistema' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
            ⚙️ Sistema
        </a>
        <a href="<?php echo base_url('settings/cupons'); ?>" 
           class="<?php echo $active_tab === 'cupons' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
            🎟️ Cupons
        </a>
        <a href="<?php echo base_url('settings/todas'); ?>" 
           class="<?php echo $active_tab === 'todas' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
            📋 Todas
        </a>
    </nav>
</div>
