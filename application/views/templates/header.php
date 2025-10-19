<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title><?php echo isset($title) ? $title : 'ConectCorretores'; ?></title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Sistema SaaS para corretores gerenciarem seus imóveis de forma profissional">
    <meta name="keywords" content="imóveis, corretores, gestão, saas, aluguel, venda">
    <meta name="author" content="Rafael Dias - doisr.com.br">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Custom Styles -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .btn {
            @apply px-4 py-2 rounded-lg font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2;
        }
        
        .btn-primary {
            @apply btn bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500;
        }
        
        .btn-secondary {
            @apply btn bg-gray-200 text-gray-900 hover:bg-gray-300 focus:ring-gray-500;
        }
        
        .btn-outline {
            @apply btn border-2 border-primary-600 text-primary-600 hover:bg-primary-50 focus:ring-primary-500;
        }
        
        .input {
            @apply w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200;
        }
        
        .card {
            @apply bg-white rounded-xl shadow-lg p-6 border border-gray-100;
        }
        
        .alert {
            @apply p-4 rounded-lg mb-4;
        }
        
        .alert-success {
            @apply alert bg-green-50 text-green-800 border border-green-200;
        }
        
        .alert-error {
            @apply alert bg-red-50 text-red-800 border border-red-200;
        }
        
        .alert-info {
            @apply alert bg-blue-50 text-blue-800 border border-blue-200;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
