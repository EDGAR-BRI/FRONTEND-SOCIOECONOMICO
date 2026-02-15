<div class="card">
    <h2 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b">6. Datos Económicos</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="transporte_id" class="label-field">Transporte</label>
            <select id="transporte_id" name="transporte_id" class="input-field">
                <option value="">Seleccione...</option>
                <?php if (isset($catalogos['transportes'])): ?>
                    <?php foreach ($catalogos['transportes'] as $item): ?>
                        <option value="<?php echo $item['id']; ?>"
                            <?php echo (isset($old['transporte_id']) && $old['transporte_id'] == $item['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($item['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div>
            <label for="dependencia_economica_id" class="label-field">Dependencia Económica</label>
            <select id="dependencia_economica_id" name="dependencia_economica_id" class="input-field">
                <option value="">Seleccione...</option>
                <?php if (isset($catalogos['dependencias_economicas'])): ?>
                    <?php foreach ($catalogos['dependencias_economicas'] as $item): ?>
                        <option value="<?php echo $item['id']; ?>"
                            <?php echo (isset($old['dependencia_economica_id']) && $old['dependencia_economica_id'] == $item['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($item['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div>
            <label for="fuente_ingreso_id" class="label-field">Fuente de Ingreso Familiar</label>
            <select id="fuente_ingreso_id" name="fuente_ingreso_id" class="input-field">
                <option value="">Seleccione...</option>
                <?php if (isset($catalogos['fuentes_ingreso'])): ?>
                    <?php foreach ($catalogos['fuentes_ingreso'] as $item): ?>
                        <option value="<?php echo $item['id']; ?>"
                            <?php echo (isset($old['fuente_ingreso_id']) && $old['fuente_ingreso_id'] == $item['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($item['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div>
            <label for="ingreso_familiar_id" class="label-field">Ingreso Familiar</label>
            <select id="ingreso_familiar_id" name="ingreso_familiar_id" class="input-field">
                <option value="">Seleccione...</option>
                <?php if (isset($catalogos['ingresos_familiares'])): ?>
                    <?php foreach ($catalogos['ingresos_familiares'] as $item): ?>
                        <option value="<?php echo $item['id']; ?>"
                            <?php echo (isset($old['ingreso_familiar_id']) && $old['ingreso_familiar_id'] == $item['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($item['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div>
            <label for="veracidad_id" class="label-field">Veracidad de la Información <span class="text-red-500">*</span></label>
            <select id="veracidad_id" name="veracidad_id" required class="input-field">
                <option value="">Seleccione...</option>
                <?php if (isset($catalogos['veracidades'])): ?>
                    <?php foreach ($catalogos['veracidades'] as $item): ?>
                        <option value="<?php echo $item['id']; ?>"
                            <?php echo (isset($old['veracidad_id']) && $old['veracidad_id'] == $item['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($item['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="md:col-span-2">
            <label for="cedula_file" class="label-field">Cédula de Identidad (Archivo)</label>
            <input type="file" id="cedula_file" name="cedula_file" accept="image/*,.pdf"
                class="input-field">
            <p class="text-sm text-gray-500 mt-1">Formatos aceptados: JPG, PNG, PDF (máx. 5MB)</p>
        </div>
    </div>
</div>