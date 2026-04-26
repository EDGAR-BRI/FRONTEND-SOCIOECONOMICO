<?php
    $dashboardData = isset($dashboard) && is_array($dashboard) ? $dashboard : [];
    $totalEncuestas = isset($dashboardData['total_encuestas']) && $dashboardData['total_encuestas'] !== null ? (int)$dashboardData['total_encuestas'] : null;
    $totalUsuarios = isset($dashboardData['total_usuarios']) && $dashboardData['total_usuarios'] !== null ? (int)$dashboardData['total_usuarios'] : null;
    $ultimaEncuesta = isset($dashboardData['ultima_encuesta']) && $dashboardData['ultima_encuesta'] !== null ? (string)$dashboardData['ultima_encuesta'] : null;

    $recientes = isset($encuestasRecientes) && is_array($encuestasRecientes) ? $encuestasRecientes : [];
?>

<!-- Dashboard Overview -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <div class="bg-white rounded-lg shadow-sm border p-5">
        <div class="flex items-center">
            <div class="p-3 rounded-full text-primary2-400 mr-4">
                <i class="fas fa-file-invoice text-3xl"></i>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500">Total Encuestas</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1"><?php echo $totalEncuestas !== null ? number_format($totalEncuestas, 0, ',', '.') : '—'; ?></h3>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border p-5">
        <div class="flex items-center">
            <div class="p-3 rounded-full text-primary2-400 mr-4">
                <i class="fas fa-users text-3xl"></i>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500">Usuarios</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1"><?php echo $totalUsuarios !== null ? number_format($totalUsuarios, 0, ',', '.') : '—'; ?></h3>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border p-5">
        <div class="flex items-center">
            <div class="p-3 rounded-full text-primary2-400 mr-4">
                <i class="fas fa-calendar-alt text-3xl"></i>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500">Última encuesta</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1"><?php echo $ultimaEncuesta !== null ? htmlspecialchars($ultimaEncuesta) : '—'; ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Últimas Respuestas -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Últimas Encuestas</h3>
            <a href="<?php echo BASE_URL; ?>/admin/respuestas" class="text-sm text-blue-500 hover:underline">Ver todas</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm">
                        <th class="py-2 px-4 border-b">Estudiante</th>
                        <th class="py-2 px-4 border-b">Cédula</th>
                        <th class="py-2 px-4 border-b">Fecha</th>
                        <th class="py-2 px-4 border-b">Estrato</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <?php if (empty($recientes)): ?>
                        <tr>
                            <td class="py-6 px-4 text-gray-500" colspan="4">No hay encuestas para mostrar.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recientes as $row): ?>
                            <?php
                                $estudiante = isset($row['estudiante']) ? (string)$row['estudiante'] : '';
                                $cedula = isset($row['cedula']) ? (string)$row['cedula'] : '';
                                $fecha = isset($row['creado']) ? (string)$row['creado'] : '';
                                $estrato = isset($row['estrato']) ? $row['estrato'] : null;
                                $isCompleta = $estrato !== null && $estrato !== '';
                            ?>
                            <tr>
                                <td class="py-3 px-4 border-b"><?php echo htmlspecialchars($estudiante ?: '—'); ?></td>
                                <td class="py-3 px-4 border-b"><?php echo htmlspecialchars($cedula ?: '—'); ?></td>
                                <td class="py-3 px-4 border-b"><?php echo htmlspecialchars($fecha ?: '—'); ?></td>
                                <td class="py-3 px-4 border-b">
                                    <?php if ($estrato): ?>
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium border border-green-200"><?php echo htmlspecialchars((string)$estrato); ?></span>
                                <?php else: ?>
                                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-medium border border-gray-200">Sin estrato</span>
                                <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Actividad Reciente -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Accesos Rápidos</h3>
        <ul class="space-y-3">
            <?php
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $dashRol = null;
                if (isset($_SESSION['auth_user']) && is_array($_SESSION['auth_user'])
                    && isset($_SESSION['auth_user']['rol']) && is_array($_SESSION['auth_user']['rol'])
                    && !empty($_SESSION['auth_user']['rol']['codigo'])
                ) {
                    $dashRol = (string)$_SESSION['auth_user']['rol']['codigo'];
                }
                $dashIsSuperAdmin = ($dashRol === 'SUPER_ADMIN');
            ?>

            <?php if ($dashIsSuperAdmin): ?>
                <li>
                    <a href="<?php echo BASE_URL; ?>/admin/usuarios" class="flex items-center p-3 hover:bg-gray-50 rounded border transition">
                        <div class="p-2 bg-blue-100 text-blue-500 rounded mr-3"><i class="fas fa-user-plus"></i></div>
                        <div>
                            <p class="font-medium text-gray-800">Añadir nuevo usuario</p>
                            <p class="text-xs text-gray-500">Gestionar permisos de acceso al dashboard.</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL; ?>/admin/catalogos" class="flex items-center p-3 hover:bg-gray-50 rounded border transition">
                        <div class="p-2 bg-purple-100 text-purple-500 rounded mr-3"><i class="fas fa-edit"></i></div>
                        <div>
                            <p class="font-medium text-gray-800">Modificar Catálogos</p>
                            <p class="text-xs text-gray-500">Añade o edita las opciones del formulario socioeconómico.</p>
                        </div>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
