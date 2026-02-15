<div class="card">
    <h2 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b">4. Datos de Vivienda</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="tipo_convivencia_id" class="label-field">Tipo de Convivencia</label>
            <select id="tipo_convivencia_id" name="tipo_convivencia_id" class="input-field">
                <option value="">Seleccione...</option>
                <?php if (isset($catalogos['tipos_convivencia'])): ?>
                    <?php foreach ($catalogos['tipos_convivencia'] as $item): ?>
                        <option value="<?php echo $item['id']; ?>"
                            <?php echo (isset($old['tipo_convivencia_id']) && $old['tipo_convivencia_id'] == $item['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($item['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div>
            <label for="tipo_vivienda_id" class="label-field">Tipo de Vivienda</label>
            <select id="tipo_vivienda_id" name="tipo_vivienda_id" class="input-field">
                <option value="">Seleccione...</option>
                <?php if (isset($catalogos['tipos_vivienda'])): ?>
                    <?php foreach ($catalogos['tipos_vivienda'] as $item): ?>
                        <option value="<?php echo $item['id']; ?>"
                            <?php echo (isset($old['tipo_vivienda_id']) && $old['tipo_vivienda_id'] == $item['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($item['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div>
            <label for="tenencia_vivienda_id" class="label-field">Tenencia de Vivienda</label>
            <select id="tenencia_vivienda_id" name="tenencia_vivienda_id" class="input-field">
                <option value="">Seleccione...</option>
                <?php if (isset($catalogos['tenencias_vivienda'])): ?>
                    <?php foreach ($catalogos['tenencias_vivienda'] as $item): ?>
                        <option value="<?php echo $item['id']; ?>"
                            <?php echo (isset($old['tenencia_vivienda_id']) && $old['tenencia_vivienda_id'] == $item['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($item['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div>
            <label for="numero_habitantes" class="label-field">Número de Habitantes</label>
            <input type="number" id="numero_habitantes" name="numero_habitantes" min="1"
                value="<?php echo isset($old['numero_habitantes']) ? htmlspecialchars($old['numero_habitantes']) : ''; ?>"
                class="input-field">
        </div>

        <div>
            <label for="numero_ocupantes_familia" class="label-field">Número de Ocupantes de la Familia</label>
            <input type="number" id="numero_ocupantes_familia" name="numero_ocupantes_familia" min="1"
                value="<?php echo isset($old['numero_ocupantes_familia']) ? htmlspecialchars($old['numero_ocupantes_familia']) : ''; ?>"
                class="input-field">
        </div>

        <!-- Ambientes de Vivienda (Checkboxes) -->
        <div class="md:col-span-2">
            <label class="label-field">Ambientes de la Vivienda</label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-2">
                <?php if (isset($catalogos['ambientes_vivienda'])): ?>
                    <?php foreach ($catalogos['ambientes_vivienda'] as $item): ?>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="ambientes_vivienda[]" value="<?php echo $item['id']; ?>"
                                <?php echo (isset($old['ambientes_vivienda']) && in_array($item['id'], $old['ambientes_vivienda'])) ? 'checked' : ''; ?>
                                class="mr-2">
                            <?php echo htmlspecialchars($item['nombre']); ?>
                        </label>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Activos de Vivienda (Checkboxes) -->
        <div class="md:col-span-2">
            <label class="label-field">Activos de la Vivienda</label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-2">
                <?php if (isset($catalogos['activos_vivienda'])): ?>
                    <?php foreach ($catalogos['activos_vivienda'] as $item): ?>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="activos_vivienda[]" value="<?php echo $item['id']; ?>"
                                <?php echo (isset($old['activos_vivienda']) && in_array($item['id'], $old['activos_vivienda'])) ? 'checked' : ''; ?>
                                class="mr-2">
                            <?php echo htmlspecialchars($item['nombre']); ?>
                        </label>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Servicios de Vivienda (Checkboxes) -->
        <div class="md:col-span-2">
            <label class="label-field">Servicios de la Vivienda</label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-2">
                <?php if (isset($catalogos['servicios_vivienda'])): ?>
                    <?php foreach ($catalogos['servicios_vivienda'] as $item): ?>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="servicios_vivienda[]" value="<?php echo $item['id']; ?>"
                                <?php echo (isset($old['servicios_vivienda']) && in_array($item['id'], $old['servicios_vivienda'])) ? 'checked' : ''; ?>
                                class="mr-2">
                            <?php echo htmlspecialchars($item['nombre']); ?>
                        </label>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Frecuencias de Servicios -->
        <div>
            <label for="frecuencia_agua_id" class="label-field">Frecuencia Servicio de Agua</label>
            <select id="frecuencia_agua_id" name="frecuencia_agua_id" class="input-field">
                <option value="">Seleccione...</option>
                <?php if (isset($catalogos['frecuencias_agua'])): ?>
                    <?php foreach ($catalogos['frecuencias_agua'] as $item): ?>
                        <option value="<?php echo $item['id']; ?>"
                            <?php echo (isset($old['frecuencia_agua_id']) && $old['frecuencia_agua_id'] == $item['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($item['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div>
            <label for="frecuencia_aseo_id" class="label-field">Frecuencia Servicio de Aseo</label>
            <select id="frecuencia_aseo_id" name="frecuencia_aseo_id" class="input-field">
                <option value="">Seleccione...</option>
                <?php if (isset($catalogos['frecuencias_aseo'])): ?>
                    <?php foreach ($catalogos['frecuencias_aseo'] as $item): ?>
                        <option value="<?php echo $item['id']; ?>"
                            <?php echo (isset($old['frecuencia_aseo_id']) && $old['frecuencia_aseo_id'] == $item['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($item['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div>
            <label for="frecuencia_electricidad_id" class="label-field">Frecuencia Servicio de Electricidad</label>
            <select id="frecuencia_electricidad_id" name="frecuencia_electricidad_id" class="input-field">
                <option value="">Seleccione...</option>
                <?php if (isset($catalogos['frecuencias_electricidad'])): ?>
                    <?php foreach ($catalogos['frecuencias_electricidad'] as $item): ?>
                        <option value="<?php echo $item['id']; ?>"
                            <?php echo (isset($old['frecuencia_electricidad_id']) && $old['frecuencia_electricidad_id'] == $item['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($item['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div>
            <label for="frecuencia_gas_id" class="label-field">Frecuencia Servicio de Gas</label>
            <select id="frecuencia_gas_id" name="frecuencia_gas_id" class="input-field">
                <option value="">Seleccione...</option>
                <?php if (isset($catalogos['frecuencias_gas'])): ?>
                    <?php foreach ($catalogos['frecuencias_gas'] as $item): ?>
                        <option value="<?php echo $item['id']; ?>"
                            <?php echo (isset($old['frecuencia_gas_id']) && $old['frecuencia_gas_id'] == $item['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($item['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>
</div>