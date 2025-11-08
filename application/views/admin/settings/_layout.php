<?php
/**
 * Layout Base para Configurações - Admin
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 08/11/2025
 */
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('templates/dashboard_header');
?>

<?php $this->load->view('templates/sidebar'); ?>

<!-- Main Content -->
<div class="lg:pl-64 min-h-screen flex flex-col">
    <!-- Top Bar -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="flex items-center justify-between px-4 py-4">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <h1 class="text-xl font-semibold text-gray-900"><?php echo isset($page_title) ? $page_title : 'Configurações'; ?></h1>
            <div class="flex items-center space-x-2">
                <a href="<?php echo base_url('settings/limpar_cache'); ?>" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1 p-4 lg:p-8 bg-gray-50">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <!-- Tabs -->
            <?php $this->load->view('admin/settings/_tabs', ['active_tab' => isset($active_tab) ? $active_tab : '']); ?>

            <!-- Alerts -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                    <div class="flex">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="ml-3 text-sm text-green-700"><?php echo $this->session->flashdata('success'); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
                    <div class="flex">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <p class="ml-3 text-sm text-red-700"><?php echo $this->session->flashdata('error'); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Content Area -->
            <?php echo isset($content) ? $content : ''; ?>

        </div>
    </main>

    <?php $this->load->view('templates/footer'); ?>
</div>
