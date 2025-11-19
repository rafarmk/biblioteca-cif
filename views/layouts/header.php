<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Biblioteca CIF'; ?></title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS de Temas Profesionales -->
    <link rel="stylesheet" href="assets/css/temas_tecnologicos.css">
    
    <!-- CSS adicional específico de la página -->
    <?php if (isset($extra_css)): ?>
        <style><?php echo $extra_css; ?></style>
    <?php endif; ?>

    <!-- JavaScript de Temas (cargar antes para evitar flash) -->
    <script>
        // Cargar tema guardado inmediatamente
        const savedTheme = localStorage.getItem('biblioteca-theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
</head>
<body>