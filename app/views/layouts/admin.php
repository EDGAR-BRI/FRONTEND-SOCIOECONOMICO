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

$themeScript = <<<'HTML'
<script>
(function () {
    const storageKey = 'socioeconomico-theme';
    const root = document.documentElement;
    const savedTheme = localStorage.getItem(storageKey);
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const theme = savedTheme || (prefersDark ? 'dark' : 'light');
    root.classList.toggle('dark', theme === 'dark');
    root.dataset.theme = theme;
}());
</script>
HTML;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? htmlspecialchars($title) : 'Panel de Administración'; ?></title>
    <link rel="icon" href="<?php echo BASE_URL; ?>/assets/FeyAlegria.svg" type="image/x-icon">
    <?php echo $themeScript; ?>
    <link rel="stylesheet" href="<?php echo $cssHref; ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="admin-shell bg-gray-100 text-gray-800 font-sans leading-normal tracking-normal flex h-screen overflow-hidden transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">

<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $sidebarRol = null;
    if (isset($_SESSION['auth_user']) && is_array($_SESSION['auth_user'])
        && isset($_SESSION['auth_user']['rol']) && is_array($_SESSION['auth_user']['rol'])
        && !empty($_SESSION['auth_user']['rol']['codigo'])
    ) {
        $sidebarRol = (string)$_SESSION['auth_user']['rol']['codigo'];
    }
    $isSuperAdmin = ($sidebarRol === 'SUPER_ADMIN');

    $current_page = isset($current_page) ? (string)$current_page : '';

    // Menú desplegable de reportes por vista.
    $reportesMenuItems = [
        [
            'key' => 'reportes_dashboard_general',
            'label' => 'Resumen General',
            'href' => BASE_URL . '/admin/reportes/dashboard-general',
        ],
        [
            'key' => 'reportes_analisis_academico',
            'label' => 'Análisis Académico',
            'href' => BASE_URL . '/admin/reportes/analisis-academico',
        ],
        [
            'key' => 'reportes_demografico_vulnerabilidad',
            'label' => 'Perfil Social',
            'href' => BASE_URL . '/admin/reportes/demografico-vulnerabilidad',
        ],
    ];

    $isReportesSection = ($current_page === 'reportes' || strpos($current_page, 'reportes_') === 0);
?>

    <!-- Sidebar -->
    <aside class="w-64 bg-white h-screen shadow-lg hidden md:block md:fixed md:inset-y-0 md:left-0 md:z-30 overflow-y-auto transition-colors duration-300 dark:bg-slate-900 dark:border-slate-700">
        <div class="p-6 border-b flex items-center gap-3 dark:border-slate-700">
            <img class="h-10 w-auto" src="<?php echo BASE_URL; ?>/assets/iujo.png" alt="IUJO Logo" onerror="this.src='https://via.placeholder.com/40'">
        </div>
        <nav class="p-4 space-y-2">
            <a href="<?php echo BASE_URL; ?>/admin" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors duration-200 <?php echo ($current_page === 'dashboard') ? 'bg-primary-50 text-primary-600 font-medium dark:bg-slate-800 dark:text-primary-300' : 'text-gray-600 hover:bg-gray-50 dark:text-slate-300 dark:hover:bg-slate-800'; ?>">
                <i class="fas fa-home w-5 text-center"></i> Panel Principal
            </a>
            <div class="space-y-1" data-dropdown data-open="<?php echo $isReportesSection ? '1' : '0'; ?>">
                <button
                    type="button"
                    class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-lg transition-colors duration-200 <?php echo $isReportesSection ? 'bg-primary-50 text-primary-600 font-medium dark:bg-slate-800 dark:text-primary-300' : 'text-gray-600 hover:bg-gray-50 dark:text-slate-300 dark:hover:bg-slate-800'; ?>"
                    aria-expanded="<?php echo $isReportesSection ? 'true' : 'false'; ?>"
                    data-dropdown-btn
                >
                    <span class="flex items-center gap-3">
                        <i class="fas fa-chart-pie w-5 text-center"></i>
                        <span>Reportes</span>
                    </span>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200 <?php echo $isReportesSection ? 'rotate-180' : ''; ?>" data-dropdown-icon></i>
                </button>

                <div class="pl-6" data-dropdown-menu>
                    <div class="space-y-1">
                        <?php foreach ($reportesMenuItems as $item):
                            $itemKey = isset($item['key']) ? (string)$item['key'] : '';
                            $itemHref = isset($item['href']) ? (string)$item['href'] : '#';
                            $itemLabel = isset($item['label']) ? (string)$item['label'] : 'Vista';
                            $isActive = ($current_page === $itemKey);
                        ?>
                            <a
                                href="<?php echo htmlspecialchars($itemHref); ?>"
                                class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition-colors duration-200 <?php echo $isActive ? 'bg-primary-50 text-primary-600 font-medium dark:bg-slate-800 dark:text-primary-300' : 'text-gray-600 hover:bg-gray-50 dark:text-slate-300 dark:hover:bg-slate-800'; ?>"
                            >
                                <span class="w-5 text-center">•</span>
                                <span><?php echo htmlspecialchars($itemLabel); ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php if ($isSuperAdmin): ?>
                <a href="<?php echo BASE_URL; ?>/admin/usuarios" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors duration-200 <?php echo ($current_page === 'users') ? 'bg-primary-50 text-primary-600 font-medium dark:bg-slate-800 dark:text-primary-300' : 'text-gray-600 hover:bg-gray-50 dark:text-slate-300 dark:hover:bg-slate-800'; ?>">
                    <i class="fas fa-users w-5 text-center"></i> Usuarios
                </a>
            <?php endif; ?>
            <a href="<?php echo BASE_URL; ?>/admin/respuestas" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors duration-200 <?php echo ($current_page === 'responses') ? 'bg-primary-50 text-primary-600 font-medium dark:bg-slate-800 dark:text-primary-300' : 'text-gray-600 hover:bg-gray-50 dark:text-slate-300 dark:hover:bg-slate-800'; ?>">
                <i class="fas fa-file-alt w-5 text-center"></i> Respuestas
            </a>
            <?php if ($isSuperAdmin): ?>
                <a href="<?php echo BASE_URL; ?>/admin/catalogos" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors duration-200 <?php echo ($current_page === 'catalogs') ? 'bg-primary-50 text-primary-600 font-medium dark:bg-slate-800 dark:text-primary-300' : 'text-gray-600 hover:bg-gray-50 dark:text-slate-300 dark:hover:bg-slate-800'; ?>">
                    <i class="fas fa-list w-5 text-center"></i> Configuración
                </a>
            <?php endif; ?>
        </nav>
    </aside>

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden transition-all duration-300 md:ml-64">
        <!-- Top Navbar -->
        <header class="bg-white shadow-sm flex items-center justify-between px-8 py-6 sticky top-0 z-20 shrink-0 transition-colors duration-300 dark:bg-slate-900 dark:border-slate-700 dark:shadow-slate-950/40">
            <div class="flex items-center h-10">
                <button id="mobile-menu-btn" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h2 class="text-xl font-semibold text-gray-800 ml-4 md:ml-0 dark:text-slate-100">
                    <?php 
                        $titles = [
                            'dashboard' => 'Panel Principal',
                            'reportes' => 'Reportes',
                            'reportes_dashboard_general' => 'Reportes · Resumen General',
                            'reportes_analisis_academico' => 'Reportes · Análisis Académico',
                            'reportes_demografico_vulnerabilidad' => 'Reportes · Perfil Socioeconómico por Carreras',
                            'users' => 'Gestión de Usuarios',
                            'responses' => 'Respuestas Recibidas',
                            'catalogs' => 'Configuración de Opciones para las Encuestas'
                        ];
                        echo isset($titles[$current_page]) ? $titles[$current_page] : 'Administración';
                    ?>
                </h2>
            </div>
            
            <div class="flex items-center gap-4">
                <label class="inline-flex items-center relative cursor-pointer shrink-0" for="themeToggleAdmin" aria-label="Cambiar entre modo claro y oscuro">
                    <input class="peer hidden" id="themeToggleAdmin" type="checkbox" />
                    <div class="relative w-[52px] h-[28px] bg-slate-200 peer-checked:bg-zinc-500 rounded-full after:absolute after:content-[''] after:w-[22px] after:h-[22px] after:bg-gradient-to-r from-orange-500 to-yellow-400 peer-checked:after:from-zinc-900 peer-checked:after:to-zinc-900 after:rounded-full after:top-[3px] after:left-[3px] active:after:w-[28px] peer-checked:after:left-[49px] peer-checked:after:translate-x-[-100%] shadow-sm duration-300 after:duration-300 after:shadow-md"></div>
                    <svg height="0" width="100" viewBox="0 0 24 24" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" class="fill-white peer-checked:opacity-60 absolute w-3.5 h-3.5 left-[6px]"><path d="M12,17c-2.76,0-5-2.24-5-5s2.24-5,5-5,5,2.24,5,5-2.24,5-5,5ZM13,0h-2V5h2V0Zm0,19h-2v5h2v-5ZM5,11H0v2H5v-2Zm19,0h-5v2h5v-2Zm-2.81-6.78l-1.41-1.41-3.54,3.54,1.41,1.41,3.54-3.54ZM7.76,17.66l-1.41-1.41-3.54,3.54,1.41,1.41,3.54-3.54Zm0-11.31l-3.54-3.54-1.41,1.41,3.54,3.54,1.41-1.41Zm13.44,13.44l-3.54-3.54-1.41,1.41,3.54,3.54,1.41-1.41Z"></path></svg>
                    <svg height="512" width="512" viewBox="0 0 24 24" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" class="fill-black opacity-60 peer-checked:opacity-70 peer-checked:fill-white absolute w-3.5 h-3.5 right-[6px]"><path d="M12.009,24A12.067,12.067,0,0,1,.075,10.725,12.121,12.121,0,0,1,10.1.152a13,13,0,0,1,5.03.206,2.5,2.5,0,0,1,1.8,1.8,2.47,2.47,0,0,1-.7,2.425c-4.559,4.168-4.165,10.645.807,14.412h0a2.5,2.5,0,0,1-.7,4.319A13.875,13.875,0,0,1,12.009,24Zm.074-22a10.776,10.776,0,0,0-1.675.127,10.1,10.1,0,0,0-8.344,8.8A9.928,9.928,0,0,0,4.581,18.7a10.473,10.473,0,0,0,11.093,2.734.5.5,0,0,0,.138-.856h0C9.883,16.1,9.417,8.087,14.865,3.124a.459.459,0,0,0,.127-.465.491.491,0,0,0-.356-.362A10.68,10.68,0,0,0,12.083,2ZM20.5,12a1,1,0,0,1-.97-.757l-.358-1.43L17.74,9.428a1,1,0,0,1,.035-1.94l1.4-.325.351-1.406a1,1,0,0,1,1.94,0l.355,1.418,1.418.355a1,1,0,0,1,0,1.94l-1.418.355-.355,1.418A1,1,0,0,1,20.5,12ZM16,14a1,1,0,0,0,2,0A1,1,0,0,0,16,14Zm6,4a1,1,0,0,0,2,0A1,1,0,0,0,22,18Z"></path></svg>
                </label>
                <div class="text-sm font-medium text-gray-600 dark:text-slate-300">
                    Admin
                </div>
                <!-- Form para Logout -->
                <form action="<?php echo BASE_URL; ?>/logout" method="POST" class="m-0">
                    <button type="submit" class="text-red-500 hover:text-red-700 font-medium flex items-center gap-2 dark:text-red-300 dark:hover:text-red-200">
                        <i class="fas fa-sign-out-alt"></i> <span class="hidden sm:inline">Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 grid grid-rows-[1fr_auto] bg-gray-100 overflow-y-auto transition-colors duration-300 dark:bg-slate-950">
            <div class="p-6">
                <!-- Renderiza la vista específica -->
                <?php echo $content; ?>
            </div>

            <!-- Footer -->
            <footer class="bg-white border-t py-4 text-center text-sm text-gray-500 transition-colors duration-300 dark:bg-slate-900 dark:border-slate-700 dark:text-slate-400">
                &copy; <?php echo date('Y'); ?> IUJO - Sistema de Administración Socioeconómico. Todos los derechos reservados.
            </footer>
        </main>
    </div>

    <script>
    (function () {
        const themeToggle = document.getElementById('themeToggleAdmin');
        const root = document.documentElement;
        if (themeToggle) {
            themeToggle.checked = root.classList.contains('dark');
            themeToggle.addEventListener('change', function () {
                const isDark = this.checked;
                root.classList.toggle('dark', isDark);
                root.dataset.theme = isDark ? 'dark' : 'light';
                localStorage.setItem('socioeconomico-theme', isDark ? 'dark' : 'light');
            });
        }

        const dropdowns = document.querySelectorAll('[data-dropdown]');
        dropdowns.forEach((root) => {
            const btn = root.querySelector('[data-dropdown-btn]');
            const menu = root.querySelector('[data-dropdown-menu]');
            const icon = root.querySelector('[data-dropdown-icon]');
            if (!btn || !menu) return;

            const setOpen = (open) => {
                btn.setAttribute('aria-expanded', open ? 'true' : 'false');
                menu.classList.toggle('hidden', !open);
                if (icon) icon.classList.toggle('rotate-180', open);
                root.setAttribute('data-open', open ? '1' : '0');
            };

            const initialOpen = root.getAttribute('data-open') === '1';
            setOpen(initialOpen);

            btn.addEventListener('click', () => {
                const isOpen = root.getAttribute('data-open') === '1';
                setOpen(!isOpen);
            });
        });
    })();
    </script>

</body>
</html>
