
    <footer>

    </footer>

    <script>
    function redirectOption(select) {
        var selectedOption = select.options[select.selectedIndex].value;
        var urlBase = "<?php echo $urlBase; ?>";

        switch (selectedOption) {
            case 'inicio':
                // Redirige a la página de perfil
                window.location.href = urlBase + 'index.php';
                break;
            case 'configuracion':
                // Redirige a la página de configuración
                window.location.href = urlBase + 'configuracion.php';
                break;
            case 'revisar-cursos':
                // Redirige a la página de revisar cursos
                window.location.href = urlBase + 'revisar_cursos.php';
                break;
            case 'cerrar-sesion':
                // Redirige a la página de cerrar sesión
                window.location.href = urlBase + 'cerrar.php';
                break;
            default:
                // No hay acción por defecto
        }
    }

</script>


</body>
</html>