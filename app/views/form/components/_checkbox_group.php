<?php

/**
 * Componente reutilizable para un grupo de checkboxes
 * 
 * Variables esperadas:
 * @param array $label Título del grupo de opciones
 * @param string $name Nombre del campo para el formulario (debe incluir [] si es múltiple, ej. "ambientes_vivienda[]")
 * @param array $options Opciones a mostrar, array con formato [['id' => '1', 'nombre' => 'Sala'], ...]
 * @param array $oldData Array con los valores seleccionados previamente
 */
?>

<div class="md:col-span-2 mb-6">
    <label class="label-field text-lg font-semibold text-slate-700 block mb-4"><?php echo htmlspecialchars($label); ?></label>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-2">
        <?php if (!empty($options)): ?>
            <?php foreach ($options as $item): ?>
                <label class="flex items-center gap-1 p-3 border border-gray-200 rounded-lg cursor-pointer transition-colors duration-200 hover:border-primary2-500 hover:bg-primary2-100">
                    <input type="checkbox"
                        name="<?php echo htmlspecialchars($name); ?>"
                        value="<?php echo $item['id']; ?>"
                        <?php echo (isset($oldData) && is_array($oldData) && in_array($item['id'], $oldData)) ? 'checked' : ''; ?>
                        class="w-5 h-5 text-primary2-600 bg-gray-100 border-gray-300 rounded focus:ring-primary2-500 accent-primary2-600 cursor-pointer flex-shrink-0 ml-1">

                    <span class="ml-3 text-sm text-slate-600 font-medium select-none leading-snug">
                        <?php echo htmlspecialchars($item['nombre']); ?>
                    </span>
                </label>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>