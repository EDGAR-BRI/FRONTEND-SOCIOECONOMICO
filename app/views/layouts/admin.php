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

    // Menú desplegable de estadísticas. Para agregar más vistas,
    // añade un ítem aquí y luego soporta la vista en AdminController@estadisticas.
    $statsMenuItems = [
        [
            'key' => 'stats_resumen',
            'label' => 'Resumen',
            'href' => BASE_URL . '/admin/estadisticas?vista=resumen',
        ],
        [
            'key' => 'stats_estratos',
            'label' => 'Estratos',
            'href' => BASE_URL . '/admin/estadisticas?vista=estratos',
        ],
        [
            'key' => 'stats_carreras',
            'label' => 'Carreras',
            'href' => BASE_URL . '/admin/estadisticas?vista=carreras',
        ],
    ];

    $isStatsSection = ($current_page === 'stats' || strpos($current_page, 'stats_') === 0);
?>

    <!-- Sidebar -->
    <aside class="w-64 bg-white min-h-screen shadow-lg hidden md:block">
        <div class="p-6 border-b flex items-center gap-3">
            <img class="h-10 w-auto" src="<?php echo BASE_URL; ?>/assets/iujo.png" alt="IUJO Logo" onerror="this.src='https://via.placeholder.com/40'">
        </div>
        <nav class="p-4 space-y-2">
            <a href="<?php echo BASE_URL; ?>/admin" class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo ($current_page === 'dashboard') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50'; ?>">
                <i class="fas fa-home w-5 text-center"></i> Dashboard
            </a>
            <div class="space-y-1" data-dropdown data-open="<?php echo $isStatsSection ? '1' : '0'; ?>">
                <button
                    type="button"
                    class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-lg <?php echo $isStatsSection ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50'; ?>"
                    aria-expanded="<?php echo $isStatsSection ? 'true' : 'false'; ?>"
                    data-dropdown-btn
                >
                    <span class="flex items-center gap-3">
                        <i class="fas fa-chart-pie w-5 text-center"></i>
                        <span>Estadísticas</span>
                    </span>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200 <?php echo $isStatsSection ? 'rotate-180' : ''; ?>" data-dropdown-icon></i>
                </button>

                <div class="pl-6" data-dropdown-menu>
                    <div class="space-y-1">
                        <?php foreach ($statsMenuItems as $item):
                            $itemKey = isset($item['key']) ? (string)$item['key'] : '';
                            $itemHref = isset($item['href']) ? (string)$item['href'] : '#';
                            $itemLabel = isset($item['label']) ? (string)$item['label'] : 'Vista';
                            $isActive = ($current_page === $itemKey);
                        ?>
                            <a
                                href="<?php echo htmlspecialchars($itemHref); ?>"
                                class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm <?php echo $isActive ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50'; ?>"
                            >
                                <span class="w-5 text-center">•</span>
                                <span><?php echo htmlspecialchars($itemLabel); ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php if ($isSuperAdmin): ?>
                <a href="<?php echo BASE_URL; ?>/admin/usuarios" class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo ($current_page === 'users') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50'; ?>">
                    <i class="fas fa-users w-5 text-center"></i> Usuarios
                </a>
            <?php endif; ?>
            <a href="<?php echo BASE_URL; ?>/admin/respuestas" class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo ($current_page === 'responses') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50'; ?>">
                <i class="fas fa-file-alt w-5 text-center"></i> Respuestas
            </a>
            <?php if ($isSuperAdmin): ?>
                <a href="<?php echo BASE_URL; ?>/admin/catalogos" class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo ($current_page === 'catalogs') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50'; ?>">
                    <i class="fas fa-list w-5 text-center"></i> Catálogos
                </a>
            <?php endif; ?>
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
                            'stats' => 'Estadísticas',
                            'stats_resumen' => 'Estadísticas · Vista 1: Dashboard General (Resumen Ejecutivo)
¿Para qué sirve?: Para que los directores o autoridades tengan una "fotografía" rápida de la situación global de la institución.

¿Qué debe contener?

Tarjetas de KPIs (Key Performance Indicators): Total de estudiantes encuestados vs. Total de población (tasa de respuesta), Moda del estrato (el estrato más común).

Gráfico de Dono o Pastel: Distribución general por Sexo (Ej. 60% Femenino, 40% Masculino).

Gráfico de Barras Verticales: Distribución general de la población por Estratos (Eje X: Estrato 1, 2, 3, etc. Eje Y: Cantidad de estudiantes).

El porqué: En esta vista no cruzamos la carrera. Solo damos totales globales. Evitamos la redundancia dejando los detalles para las siguientes vistas.

Vista 2: Análisis Académico (Filtro por Carreras)
¿Para qué sirve?: Para que los coordinadores de cada facultad o carrera entiendan la realidad socioeconómica específica de sus estudiantes.

¿Qué debe contener?

Filtros principales en la parte superior: Un selector (Dropdown) para filtrar por "Carrera" o "Facultad".

Gráfico de Barras Apiladas (Stacked Bar Chart) al 100%: Si no filtran una carrera específica, muestras todas las carreras en el Eje X, y cada barra representa el 100% de los estudiantes de esa carrera, segmentada por colores según sus estratos.

El porqué: Aquí aplicamos el principio de consolidación. En lugar de hacer una vista por cada carrera (lo cual sería super redundante), haces una sola vista interactiva. La interactividad elimina la redundancia visual.

Vista 3: Análisis Demográfico y de Vulnerabilidad (Cruces Complejos)
¿Para qué sirve?: Para analistas de bienestar social que necesitan encontrar "focos de vulnerabilidad" (por ejemplo, descubrir si la mayoría de las mujeres de cierta carrera están en los estratos más bajos).

¿Qué debe contener?

Mapa de Calor (Heatmap): Eje X: Estratos. Eje Y: Carreras. El color de la celda es más intenso (ej. rojo) donde haya mayor cantidad de estudiantes. Esto permite ver visualmente dónde se concentra la población.

Gráfico de Barras Agrupadas (Grouped Bar Chart): Eje X: Estratos. Por cada estrato, dos barras de lado a lado (Femenino y Masculino).

El porqué: Solo en esta vista se cruzan 3 o más variables (Sexo, Estrato, Carrera). Mantener esta complejidad aislada en su propia pestaña evita que las vistas más simples se ensucien.Resumen',
                            'stats_estratos' => 'Estadísticas · Estratos',
                            'stats_carreras' => 'Estadísticas · Carreras',
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

    <script>
    (function () {
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
