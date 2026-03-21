<?php
// para que se pueda mover el proyecto a cualquier carpeta y no se rompan las imagenes
if (!isset($assetBase)) {
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
    $assetBase = $basePath . '/assets';
}

// Cache busting: avoid browser hard-cache when output.css changes.
// Determine which CSS file exists in the current entrypoint's assets folder.
// todo esto es para que recargue el css en caso de estar en modo dev o actualizar los estilos para que
// recargen y los vuelva a cargar para tomar los nuevos cambios 
$cssCandidates = ['output.css'];
$cssFile = 'output.css';
$cssVersion = null;

$scriptFilename = $_SERVER['SCRIPT_FILENAME'] ?? '';
$assetsDiskDir = $scriptFilename ? (dirname($scriptFilename) . DIRECTORY_SEPARATOR . 'assets') : '';
foreach ($cssCandidates as $candidate) {
    $candidateDiskPath = $assetsDiskDir
        ? ($assetsDiskDir . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . $candidate)
        : '';
    if ($candidateDiskPath && is_file($candidateDiskPath)) {
        $cssFile = $candidate;
        $cssVersion = @filemtime($candidateDiskPath) ?: null;
        break;
    }
}

$cssHref = $assetBase . '/css/' . $cssFile;
if ($cssVersion !== null) {
    $cssHref .= '?v=' . $cssVersion;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? htmlspecialchars($title) : 'Panel de Administración'; ?></title>
    <link rel="icon" href="<?php echo BASE_URL; ?>/assets/FeyAlegria.svg" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo $cssHref; ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800 font-sans leading-normal tracking-normal flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-white min-h-screen shadow-lg hidden md:block">
        <div class="p-6 border-b flex items-center gap-3">
            <img class="h-10 w-auto" src="<?php echo BASE_URL; ?>/assets/iujo.png" alt="IUJO Logo" onerror="this.src='https://via.placeholder.com/40'">
        </div>
        <nav class="p-4 space-y-2">
            <a href="<?php echo BASE_URL; ?>/admin" class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo ($current_page === 'dashboard') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50'; ?>">
                <i class="fas fa-home w-5 text-center"></i> Dashboard
            </a>
            <a href="<?php echo BASE_URL; ?>/admin/usuarios" class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo ($current_page === 'users') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50'; ?>">
                <i class="fas fa-users w-5 text-center"></i> Usuarios
            </a>
            <a href="<?php echo BASE_URL; ?>/admin/respuestas" class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo ($current_page === 'responses') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50'; ?>">
                <i class="fas fa-file-alt w-5 text-center"></i> Respuestas
            </a>
            <!-- <a href="<?php echo BASE_URL; ?>/admin/catalogos" class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo ($current_page === 'catalogs') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50'; ?>">
                <i class="fas fa-list w-5 text-center"></i> Catálogos
            </a> -->
        </nav>
    </aside>

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col min-h-screen transition-all duration-300">
        <!-- Top Navbar -->
        <header class="bg-white shadow-sm flex items-center justify-between px-8 py-6">
            <div class="flex items-center h-10">
                <button id="mobile-menu-btn" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h2 class="text-xl font-semibold text-gray-800 ml-4 md:ml-0">
                    <?php 
                        $titles = [
                            'dashboard' => 'Dashboard Overview',
                            'users' => 'Gestión de Usuarios',
                            'responses' => 'Respuestas Recibidas',
                            'catalogs' => 'Gestión de Catálogos (Opciones)'
                        ];
                        echo isset($titles[$current_page]) ? $titles[$current_page] : 'Administración';
                    ?>
                </h2>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="text-sm font-medium text-gray-600">
                    Admin
                </div>
                <!-- Form para Logout -->
                <form action="<?php echo BASE_URL; ?>/logout" method="POST" class="m-0">
                    <button type="submit" class="text-red-500 hover:text-red-700 font-medium flex items-center gap-2">
                        <i class="fas fa-sign-out-alt"></i> <span class="hidden sm:inline">Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 p-6 bg-gray-100">
            <!-- Renderiza la vista específica -->
            <?php echo $content; ?>
        </main>
        
        <!-- Footer -->
        <footer class="bg-white border-t py-4 text-center text-sm text-gray-500">
            &copy; <?php echo date('Y'); ?> IUJO - Sistema de Administración Socioeconómico. Todos los derechos reservados.
        </footer>
    </div>

</body>
</html>
