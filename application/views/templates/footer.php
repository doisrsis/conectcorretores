    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center text-gray-600 text-sm">
                <p>&copy; <?php echo date('Y'); ?> ConectCorretores - Desenvolvido por 
                    <a href="https://doisr.com.br" target="_blank" rel="noopener noreferrer" class="text-primary-600 hover:text-primary-700 font-medium">
                        Rafael Dias - doisr.com.br
                    </a>
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Auto-hide alerts após 5 segundos
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });
        });
    </script>
</body>
</html>
