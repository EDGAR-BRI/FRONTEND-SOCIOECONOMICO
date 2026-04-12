<div class="grid grid-cols-1 md:grid-cols-4 gap-6">

    <div class="bg-white rounded-lg shadow-sm border p-4 md:col-span-1 h-fit">
        <h3 class="font-bold text-gray-800 mb-4 px-2">Categorías</h3>
        <ul class="space-y-1 text-sm">
            <?php if (!empty($catalogosMenu) && is_array($catalogosMenu)): ?>
                <?php foreach ($catalogosMenu as $item): ?>
                    <?php
                        if (!is_array($item) || empty($item['resource'])) {
                            continue;
                        }

                        $itemResource = (string)$item['resource'];
                        $itemLabel = !empty($item['label']) ? (string)$item['label'] : $itemResource;
                        $isActive = isset($resource) && (string)$resource === $itemResource;

                        $btnClass = $isActive
                            ? 'bg-primary-50 text-primary-600 font-medium'
                            : 'hover:bg-gray-50 text-gray-600';
                    ?>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/admin/catalogos?resource=<?php echo urlencode($itemResource); ?>" class="block w-full text-left px-3 py-2 rounded-md <?php echo $btnClass; ?>">
                            <?php echo htmlspecialchars($itemLabel); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="text-sm text-gray-500 px-3 py-2">No hay catálogos disponibles.</li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="bg-white rounded-lg shadow-sm border p-6 md:col-span-3">
        <?php if (!empty($apiError) && is_array($apiError)): ?>
            <div class="mb-6 p-4 rounded border border-red-200 bg-red-50 text-red-700 text-sm">
                <?php echo htmlspecialchars(isset($apiError['message']) ? (string)$apiError['message'] : 'Error al cargar datos'); ?>
            </div>
        <?php endif; ?>

        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars(isset($catalogoLabel) ? (string)$catalogoLabel : 'Catálogo'); ?></h3>
                <p class="text-sm text-gray-500">Gestiona las opciones disponibles para este campo.</p>
            </div>
            <button class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded shadow-sm text-sm font-medium transition">
                <i class="fas fa-plus mr-2"></i> Añadir Opción
            </button>
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 border-y">
                    <th class="py-3 px-4 font-semibold text-sm w-16">ID/Valor</th>
                    <th class="py-3 px-4 font-semibold text-sm">Nombre a mostrar</th>
                    <th class="py-3 px-4 font-semibold text-sm w-24 text-center">Estado</th>
                    <th class="py-3 px-4 font-semibold text-sm w-24 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y">
                <?php if (!empty($catalogoItems) && is_array($catalogoItems)): ?>
                    <?php foreach ($catalogoItems as $row): ?>
                        <?php
                            if (!is_array($row)) {
                                continue;
                            }
                            $id = isset($row['id']) ? (string)$row['id'] : '';
                            $nombre = isset($row['nombre']) ? (string)$row['nombre'] : '';
                            $activo = isset($row['activo']) ? (int)$row['activo'] : 1;
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 text-gray-500"><?php echo htmlspecialchars($id); ?></td>
                            <td class="py-3 px-4 font-medium text-gray-800"><?php echo htmlspecialchars($nombre); ?></td>
                            <td class="py-3 px-4 text-center">
                                <?php if ($activo === 1): ?>
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Activo</span>
                                <?php else: ?>
                                    <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <button class="text-blue-500 hover:text-blue-700 mx-1" type="button" title="Editar (pendiente)"><i class="fas fa-edit"></i></button>
                                <button class="text-red-500 hover:text-red-700 mx-1" type="button" title="Eliminar (pendiente)"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="py-6 px-4 text-center text-gray-500">No hay opciones para este catálogo.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
