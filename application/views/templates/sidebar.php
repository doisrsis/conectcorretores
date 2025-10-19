<!-- Sidebar -->
<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out lg:translate-x-0" 
       id="sidebar"
       x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    
    <!-- Header -->
    <div class="flex items-center justify-between p-4 border-b border-gray-200">
        <a href="<?php echo base_url(); ?>" class="flex items-center space-x-2">
            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <span class="text-xl font-bold text-gray-900">ConectCorretores</span>
        </a>
        
        <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- User Info -->
    <div class="p-4 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                <span class="text-primary-600 font-semibold text-lg">
                    <?php echo strtoupper(substr($this->session->userdata('nome'), 0, 1)); ?>
                </span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">
                    <?php echo $this->session->userdata('nome'); ?>
                </p>
                <p class="text-xs text-gray-500 truncate">
                    <?php echo $this->session->userdata('email'); ?>
                </p>
            </div>
        </div>
        
        <?php if ($this->session->userdata('role') === 'admin'): ?>
            <div class="mt-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Administrador
                </span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Menu -->
    <nav class="flex-1 overflow-y-auto p-4">
        <ul class="space-y-1">
            <?php
            $current_page = isset($page) ? $page : '';
            
            // Menu do Corretor
            $menu_items = [
                [
                    'label' => 'Dashboard',
                    'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                    'href' => 'dashboard',
                    'page' => 'dashboard',
                    'roles' => ['corretor', 'admin']
                ],
                [
                    'label' => 'Imóveis',
                    'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                    'href' => 'imoveis',
                    'page' => 'imoveis',
                    'roles' => ['corretor', 'admin']
                ],
                [
                    'label' => 'Planos',
                    'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
                    'href' => 'planos',
                    'page' => 'planos',
                    'roles' => ['corretor']
                ],
                [
                    'label' => 'Meu Perfil',
                    'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                    'href' => 'perfil',
                    'page' => 'perfil',
                    'roles' => ['corretor', 'admin']
                ],
            ];

            // Menu Admin
            if ($this->session->userdata('role') === 'admin') {
                $menu_items = array_merge($menu_items, [
                    [
                        'label' => 'Admin Dashboard',
                        'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                        'href' => 'admin/dashboard',
                        'page' => 'admin_dashboard',
                        'roles' => ['admin']
                    ],
                    [
                        'label' => 'Usuários',
                        'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                        'href' => 'admin/usuarios',
                        'page' => 'admin_usuarios',
                        'roles' => ['admin']
                    ],
                ]);
            }

            foreach ($menu_items as $item):
                if (in_array($this->session->userdata('role'), $item['roles'])):
                    $active = ($current_page === $item['page']);
            ?>
                <li>
                    <a href="<?php echo base_url($item['href']); ?>" 
                       class="flex items-center px-4 py-3 text-gray-700 hover:bg-primary-50 hover:text-primary-700 rounded-lg transition-colors duration-200 <?php echo $active ? 'bg-primary-50 text-primary-700 font-medium' : ''; ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo $item['icon']; ?>"></path>
                        </svg>
                        <span class="ml-3"><?php echo $item['label']; ?></span>
                    </a>
                </li>
            <?php 
                endif;
            endforeach; 
            ?>
        </ul>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-gray-200">
        <a href="<?php echo base_url('logout'); ?>" 
           class="flex items-center px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            <span class="ml-3">Sair</span>
        </a>
    </div>
</aside>

<!-- Overlay para mobile -->
<div x-show="sidebarOpen" 
     @click="sidebarOpen = false"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
     style="display: none;">
</div>
