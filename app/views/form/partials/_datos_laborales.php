<div class="card">
    <h2 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b">3. Datos Laborales</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="condicion_laboral_id" class="label-field">Condición Laboral</label>
            <select id="condicion_laboral_id" name="condicion_laboral_id" class="input-field">
                <option value="">Seleccione...</option>
                <?php if (isset($catalogos['condiciones_laborales'])): ?>
                    <?php foreach ($catalogos['condiciones_laborales'] as $item): ?>
                        <option value="<?php echo $item['id']; ?>"
                            <?php echo (isset($old['condicion_laboral_id']) && $old['condicion_laboral_id'] == $item['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($item['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div>
            <label for="relacion_laboral_id" class="label-field">Relación Laboral</label>
            <select id="relacion_laboral_id" name="relacion_laboral_id" class="input-field">
                <option value="">Seleccione...</option>
                <?php if (isset($catalogos['relaciones_laborales'])): ?>
                    <?php foreach ($catalogos['relaciones_laborales'] as $item): ?>
                        <option value="<?php echo $item['id']; ?>"
                            <?php echo (isset($old['relacion_laboral_id']) && $old['relacion_laboral_id'] == $item['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($item['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div>
            <label for="tipo_organizacion_id" class="label-field">Tipo de Organización</label>
            <select id="tipo_organizacion_id" name="tipo_organizacion_id" class="input-field">
                <option value="">Seleccione...</option>
                <?php if (isset($catalogos['tipos_organizacion'])): ?>
                    <?php foreach ($catalogos['tipos_organizacion'] as $item): ?>
                        <option value="<?php echo $item['id']; ?>"
                            <?php echo (isset($old['tipo_organizacion_id']) && $old['tipo_organizacion_id'] == $item['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($item['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div>
            <label for="sector_trabajo_id" class="label-field">Sector de Trabajo</label>
            <select id="sector_trabajo_id" name="sector_trabajo_id" class="input-field">
                <option value="">Seleccione...</option>
                <?php if (isset($catalogos['sectores_trabajo'])): ?>
                    <?php foreach ($catalogos['sectores_trabajo'] as $item): ?>
                        <option value="<?php echo $item['id']; ?>"
                            <?php echo (isset($old['sector_trabajo_id']) && $old['sector_trabajo_id'] == $item['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($item['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div>
            <label for="categoria_ocupacional_id" class="label-field">Categoría Ocupacional</label>
            <select id="categoria_ocupacional_id" name="categoria_ocupacional_id" class="input-field">
                <option value="">Seleccione...</option>
                <?php if (isset($catalogos['categorias_ocupacionales'])): ?>
                    <?php foreach ($catalogos['categorias_ocupacionales'] as $item): ?>
                        <option value="<?php echo $item['id']; ?>"
                            <?php echo (isset($old['categoria_ocupacional_id']) && $old['categoria_ocupacional_id'] == $item['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($item['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>
</div>