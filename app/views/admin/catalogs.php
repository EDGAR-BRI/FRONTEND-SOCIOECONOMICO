<div class="grid grid-cols-1 md:grid-cols-4 gap-6">

    <?php
        $resource = isset($resource) ? (string)$resource : '';
        $institutoId = isset($institutoId) && $institutoId !== null ? (int)$institutoId : null;
        $currentTenantScoped = !empty($currentTenantScoped);
        $institutos = isset($institutos) && is_array($institutos) ? $institutos : [];
        $editId = isset($editId) && $editId !== null ? (int)$editId : null;
        $editItem = isset($editItem) && is_array($editItem) ? $editItem : null;

        $flashClass = null;
        if (!empty($flash) && is_array($flash)) {
            $flashClass = (($flash['type'] ?? '') === 'success')
                ? 'bg-green-50 border-green-200 text-green-700'
                : 'bg-red-50 border-red-200 text-red-700';
        }

        $buildCatalogUrl = function ($res, $extra = []) use ($institutoId, $currentTenantScoped) {
            $qs = array_merge(['resource' => $res], $extra);
            if (!empty($institutoId)) {
                $qs['instituto_id'] = (int)$institutoId;
            }
            return BASE_URL . '/admin/catalogos?' . http_build_query(array_filter($qs, function ($v) {
                return $v !== null && $v !== '';
            }));
        };

        $extraCols = [];
        $candidates = ['siglas', 'codigo', 'numero', 'valor_estrato'];
        $resourcesWithValorEstrato = ['tipo-vivienda', 'fuente-ingreso', 'nivel-educacion'];
        if (!empty($catalogoItems) && is_array($catalogoItems)) {
            foreach ($catalogoItems as $r) {
                if (!is_array($r)) {
                    continue;
                }
                foreach ($candidates as $c) {
                    if (!in_array($c, $extraCols, true) && array_key_exists($c, $r)) {
                        $extraCols[] = $c;
                    }
                }
            }
        }
        if (is_array($editItem)) {
            foreach ($candidates as $c) {
                if (!in_array($c, $extraCols, true) && array_key_exists($c, $editItem)) {
                    $extraCols[] = $c;
                }
            }
        }

        if (in_array($resource, $resourcesWithValorEstrato, true) && !in_array('valor_estrato', $extraCols, true)) {
            $extraCols[] = 'valor_estrato';
        }

        $fieldConfigByResource = [
            'instituto' => ['siglas', 'nombre'],
            'semestre' => ['nombre', 'numero'],
            'rol' => ['nombre', 'codigo'],
        ];
        $defaultFields = ['nombre'];
        $fields = isset($fieldConfigByResource[$resource]) ? $fieldConfigByResource[$resource] : $defaultFields;
        if (in_array('valor_estrato', $extraCols, true) && !in_array('valor_estrato', $fields, true)) {
            $fields[] = 'valor_estrato';
        }
    ?>

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
                        <a href="<?php echo htmlspecialchars($buildCatalogUrl($itemResource)); ?>" class="block w-full text-left px-3 py-2 rounded-md <?php echo $btnClass; ?>">
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
        <?php if (!empty($flash) && is_array($flash)): ?>
            <div class="mb-6 p-4 rounded border <?php echo htmlspecialchars((string)$flashClass); ?> text-sm">
                <div class="font-medium"><?php echo htmlspecialchars((string)($flash['message'] ?? '')); ?></div>
                <?php if (!empty($flash['errors']) && is_array($flash['errors'])): ?>
                    <ul class="mt-2 text-sm list-disc pl-5">
                        <?php foreach ($flash['errors'] as $field => $errs): ?>
                            <?php if (is_array($errs)): ?>
                                <?php foreach ($errs as $err): ?>
                                    <li><?php echo htmlspecialchars((string)$err); ?></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($apiError) && is_array($apiError)): ?>
            <div class="mb-6 p-4 rounded border border-red-200 bg-red-50 text-red-700 text-sm">
                <?php echo htmlspecialchars(isset($apiError['message']) ? (string)$apiError['message'] : 'Error al cargar datos'); ?>
            </div>
        <?php endif; ?>

        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars(isset($catalogoLabel) ? (string)$catalogoLabel : 'Catálogo'); ?></h3>
                <p class="text-sm text-gray-500">Gestiona las opciones disponibles para este campo.</p>
            </div>
            <?php if ($currentTenantScoped): ?>
                <form method="GET" action="<?php echo BASE_URL; ?>/admin/catalogos" class="flex items-center gap-2">
                    <input type="hidden" name="resource" value="<?php echo htmlspecialchars($resource); ?>">
                    <label class="text-sm text-gray-600" for="instituto_id">Sede</label>
                    <select id="instituto_id" name="instituto_id" class="border border-gray-300 rounded-md p-2 text-sm focus:ring-primary-500 focus:border-primary-500 outline-none">
                        <?php foreach ($institutos as $inst): ?>
                            <?php
                                if (!is_array($inst) || !isset($inst['id'])) {
                                    continue;
                                }
                                $iid = (int)$inst['id'];
                                $iname = isset($inst['nombre']) ? (string)$inst['nombre'] : ('Instituto #' . $iid);
                            ?>
                            <option value="<?php echo (int)$iid; ?>" <?php echo (!empty($institutoId) && (int)$institutoId === $iid) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($iname); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-3 py-2 rounded shadow-sm text-sm font-medium transition">Cambiar</button>
                </form>
            <?php endif; ?>
        </div>

        <!-- Formulario Crear/Editar -->
        <div class="mb-6 p-4 border rounded bg-gray-50">
            <?php if (!empty($editId) && is_array($editItem)): ?>
                <div class="flex items-center justify-between mb-3">
                    <div class="font-medium text-gray-800">Editar registro #<?php echo (int)$editId; ?></div>
                    <a class="text-sm text-gray-600 hover:text-gray-800" href="<?php echo htmlspecialchars($buildCatalogUrl($resource)); ?>">Cancelar</a>
                </div>
                <form method="POST" action="<?php echo BASE_URL; ?>/admin/catalogos/update/<?php echo (int)$editId; ?>" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <input type="hidden" name="resource" value="<?php echo htmlspecialchars($resource); ?>">
                    <?php if ($currentTenantScoped && !empty($institutoId)): ?>
                        <input type="hidden" name="instituto_id" value="<?php echo (int)$institutoId; ?>">
                    <?php endif; ?>

                    <?php foreach ($fields as $f): ?>
                        <?php
                            $label = $f;
                            if ($f === 'valor_estrato') $label = 'Valor estrato';
                            if ($f === 'siglas') $label = 'Siglas';
                            if ($f === 'codigo') $label = 'Código';
                            if ($f === 'numero') $label = 'Número';
                            if ($f === 'nombre') $label = 'Nombre';
                            $val = isset($editItem[$f]) ? (string)$editItem[$f] : '';
                            $type = ($f === 'numero' || $f === 'valor_estrato') ? 'number' : 'text';
                        ?>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1"><?php echo htmlspecialchars($label); ?></label>
                            <input
                                type="<?php echo $type; ?>"
                                name="<?php echo htmlspecialchars($f); ?>"
                                value="<?php echo htmlspecialchars($val); ?>"
                                class="border border-gray-300 rounded-md p-2 w-full focus:ring-primary-500 focus:border-primary-500 outline-none text-sm"
                                <?php echo ($type === 'number') ? 'step="1"' : ''; ?>
                            >
                        </div>
                    <?php endforeach; ?>

                    <div class="md:col-span-3 flex justify-end">
                        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded shadow-sm text-sm font-medium transition">Guardar cambios</button>
                    </div>
                </form>
            <?php else: ?>
                <div class="font-medium text-gray-800 mb-3">Añadir nuevo registro</div>
                <form method="POST" action="<?php echo BASE_URL; ?>/admin/catalogos/create" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <input type="hidden" name="resource" value="<?php echo htmlspecialchars($resource); ?>">
                    <?php if ($currentTenantScoped && !empty($institutoId)): ?>
                        <input type="hidden" name="instituto_id" value="<?php echo (int)$institutoId; ?>">
                    <?php endif; ?>

                    <?php foreach ($fields as $f): ?>
                        <?php
                            $label = $f;
                            if ($f === 'valor_estrato') $label = 'Valor estrato';
                            if ($f === 'siglas') $label = 'Siglas';
                            if ($f === 'codigo') $label = 'Código';
                            if ($f === 'numero') $label = 'Número';
                            if ($f === 'nombre') $label = 'Nombre';
                            $type = ($f === 'numero' || $f === 'valor_estrato') ? 'number' : 'text';
                        ?>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1"><?php echo htmlspecialchars($label); ?></label>
                            <input
                                type="<?php echo $type; ?>"
                                name="<?php echo htmlspecialchars($f); ?>"
                                value=""
                                class="border border-gray-300 rounded-md p-2 w-full focus:ring-primary-500 focus:border-primary-500 outline-none text-sm"
                                <?php echo ($type === 'number') ? 'step="1"' : ''; ?>
                            >
                        </div>
                    <?php endforeach; ?>

                    <div class="md:col-span-3 flex justify-end">
                        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded shadow-sm text-sm font-medium transition">Crear</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 border-y">
                    <th class="py-3 px-4 font-semibold text-sm w-16">ID/Valor</th>
                    <th class="py-3 px-4 font-semibold text-sm">Nombre a mostrar</th>
                    <?php foreach ($extraCols as $c): ?>
                        <th class="py-3 px-4 font-semibold text-sm"><?php echo htmlspecialchars((string)$c); ?></th>
                    <?php endforeach; ?>
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
                            <?php foreach ($extraCols as $c): ?>
                                <?php $v = array_key_exists($c, $row) ? (string)$row[$c] : ''; ?>
                                <td class="py-3 px-4 text-gray-700"><?php echo htmlspecialchars($v); ?></td>
                            <?php endforeach; ?>
                            <td class="py-3 px-4 text-center">
                                <?php if ($activo === 1): ?>
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Activo</span>
                                <?php else: ?>
                                    <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a class="text-blue-500 hover:text-blue-700 mx-1" href="<?php echo htmlspecialchars($buildCatalogUrl($resource, ['edit_id' => (int)$id])); ?>" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <?php if ($activo === 1): ?>
                                    <form method="POST" action="<?php echo BASE_URL; ?>/admin/catalogos/delete/<?php echo (int)$id; ?>" class="inline">
                                        <input type="hidden" name="resource" value="<?php echo htmlspecialchars($resource); ?>">
                                        <?php if ($currentTenantScoped && !empty($institutoId)): ?>
                                            <input type="hidden" name="instituto_id" value="<?php echo (int)$institutoId; ?>">
                                        <?php endif; ?>
                                        <button class="text-red-500 hover:text-red-700 mx-1" type="submit" title="Desactivar" onclick="return confirm('¿Desactivar este registro?');">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <form method="POST" action="<?php echo BASE_URL; ?>/admin/catalogos/restore/<?php echo (int)$id; ?>" class="inline">
                                        <input type="hidden" name="resource" value="<?php echo htmlspecialchars($resource); ?>">
                                        <?php if ($currentTenantScoped && !empty($institutoId)): ?>
                                            <input type="hidden" name="instituto_id" value="<?php echo (int)$institutoId; ?>">
                                        <?php endif; ?>
                                        <button class="text-green-600 hover:text-green-800 mx-1" type="submit" title="Restaurar" onclick="return confirm('¿Restaurar este registro?');">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?php echo (int)(4 + count($extraCols)); ?>" class="py-6 px-4 text-center text-gray-500">No hay opciones para este catálogo.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
