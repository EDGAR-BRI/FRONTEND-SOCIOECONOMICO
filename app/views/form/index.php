<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="card mb-6">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Formulario Socioeconómico</h1>
                <p class="text-gray-600">IUJO Barquisimeto - Sistema de Registro</p>
            </div>
        </div>

        <!-- Errores Generales -->
        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <p class="font-bold mb-2">Por favor corrija los siguientes errores:</p>
                <ul class="list-disc list-inside">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="/submit" method="POST" class="space-y-6" enctype="multipart/form-data">

            <!-- SECCIÓN 1: DATOS PERSONALES -->
            <div class="card">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b">1. Datos Personales</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nombres" class="label-field">Nombres <span class="text-red-500">*</span></label>
                        <input type="text" id="nombres" name="nombres" required
                            value="<?php echo isset($old['nombres']) ? htmlspecialchars($old['nombres']) : ''; ?>"
                            class="input-field">
                    </div>

                    <div>
                        <label for="apellidos" class="label-field">Apellidos <span class="text-red-500">*</span></label>
                        <input type="text" id="apellidos" name="apellidos" required
                            value="<?php echo isset($old['apellidos']) ? htmlspecialchars($old['apellidos']) : ''; ?>"
                            class="input-field">
                    </div>

                    <div>
                        <label for="cedula" class="label-field">Cédula <span class="text-red-500">*</span></label>
                        <input type="text" id="cedula" name="cedula" required
                            value="<?php echo isset($old['cedula']) ? htmlspecialchars($old['cedula']) : ''; ?>"
                            class="input-field" placeholder="V-12345678">
                    </div>

                    <div>
                        <label for="nacionalidad_id" class="label-field">Nacionalidad <span class="text-red-500">*</span></label>
                        <select id="nacionalidad_id" name="nacionalidad_id" required class="input-field">
                            <option value="">Seleccione...</option>
                            <?php if (isset($catalogos['nacionalidades'])): ?>
                                <?php foreach ($catalogos['nacionalidades'] as $item): ?>
                                    <option value="<?php echo $item['id']; ?>"
                                        <?php echo (isset($old['nacionalidad_id']) && $old['nacionalidad_id'] == $item['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($item['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div>
                        <label for="sexo_id" class="label-field">Sexo <span class="text-red-500">*</span></label>
                        <select id="sexo_id" name="sexo_id" required class="input-field">
                            <option value="">Seleccione...</option>
                            <?php if (isset($catalogos['sexos'])): ?>
                                <?php foreach ($catalogos['sexos'] as $item): ?>
                                    <option value="<?php echo $item['id']; ?>"
                                        <?php echo (isset($old['sexo_id']) && $old['sexo_id'] == $item['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($item['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div>
                        <label for="fecha_nacimiento" class="label-field">Fecha de Nacimiento <span class="text-red-500">*</span></label>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required
                            value="<?php echo isset($old['fecha_nacimiento']) ? htmlspecialchars($old['fecha_nacimiento']) : ''; ?>"
                            class="input-field">
                    </div>

                    <div>
                        <label for="email" class="label-field">Correo Electrónico <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" required
                            value="<?php echo isset($old['email']) ? htmlspecialchars($old['email']) : ''; ?>"
                            class="input-field">
                    </div>

                    <div>
                        <label for="telefono" class="label-field">Teléfono <span class="text-red-500">*</span></label>
                        <input type="tel" id="telefono" name="telefono" required
                            value="<?php echo isset($old['telefono']) ? htmlspecialchars($old['telefono']) : ''; ?>"
                            class="input-field" placeholder="0414-1234567">
                    </div>

                    <div>
                        <label for="estado_civil_id" class="label-field">Estado Civil <span class="text-red-500">*</span></label>
                        <select id="estado_civil_id" name="estado_civil_id" required class="input-field">
                            <option value="">Seleccione...</option>
                            <?php if (isset($catalogos['estados_civiles'])): ?>
                                <?php foreach ($catalogos['estados_civiles'] as $item): ?>
                                    <option value="<?php echo $item['id']; ?>"
                                        <?php echo (isset($old['estado_civil_id']) && $old['estado_civil_id'] == $item['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($item['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="direccion" class="label-field">Dirección <span class="text-red-500">*</span></label>
                        <textarea id="direccion" name="direccion" required rows="3"
                            class="input-field"><?php echo isset($old['direccion']) ? htmlspecialchars($old['direccion']) : ''; ?></textarea>
                    </div>

                    <div>
                        <label class="label-field">¿Tiene hijos?</label>
                        <div class="flex gap-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="hijos" value="1"
                                    <?php echo (isset($old['hijos']) && $old['hijos'] == '1') ? 'checked' : ''; ?>
                                    class="mr-2">
                                Sí
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="hijos" value="0"
                                    <?php echo (!isset($old['hijos']) || $old['hijos'] == '0') ? 'checked' : ''; ?>
                                    class="mr-2">
                                No
                            </label>
                        </div>
                    </div>

                    <div id="numero_hijos_container" style="display: none;">
                        <label for="numero_hijos" class="label-field">Número de Hijos</label>
                        <input type="number" id="numero_hijos" name="numero_hijos" min="0"
                            value="<?php echo isset($old['numero_hijos']) ? htmlspecialchars($old['numero_hijos']) : '0'; ?>"
                            class="input-field">
                    </div>

                    <div>
                        <label for="discapacidad" class="label-field">Discapacidad (si aplica)</label>
                        <input type="text" id="discapacidad" name="discapacidad"
                            value="<?php echo isset($old['discapacidad']) ? htmlspecialchars($old['discapacidad']) : ''; ?>"
                            class="input-field">
                    </div>

                    <div>
                        <label for="enfermedad_cronica" class="label-field">Enfermedad Crónica (si aplica)</label>
                        <input type="text" id="enfermedad_cronica" name="enfermedad_cronica"
                            value="<?php echo isset($old['enfermedad_cronica']) ? htmlspecialchars($old['enfermedad_cronica']) : ''; ?>"
                            class="input-field">
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 2: DATOS ACADÉMICOS -->
            <div class="card">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b">2. Datos Académicos</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tipo_estudiante_id" class="label-field">Tipo de Estudiante <span class="text-red-500">*</span></label>
                        <select id="tipo_estudiante_id" name="tipo_estudiante_id" required class="input-field">
                            <option value="">Seleccione...</option>
                            <?php if (isset($catalogos['tipos_estudiante'])): ?>
                                <?php foreach ($catalogos['tipos_estudiante'] as $item): ?>
                                    <option value="<?php echo $item['id']; ?>"
                                        <?php echo (isset($old['tipo_estudiante_id']) && $old['tipo_estudiante_id'] == $item['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($item['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div>
                        <label for="carrera_id" class="label-field">Carrera <span class="text-red-500">*</span></label>
                        <select id="carrera_id" name="carrera_id" required class="input-field">
                            <option value="">Seleccione...</option>
                            <?php if (isset($catalogos['carreras'])): ?>
                                <?php foreach ($catalogos['carreras'] as $item): ?>
                                    <option value="<?php echo $item['id']; ?>"
                                        <?php echo (isset($old['carrera_id']) && $old['carrera_id'] == $item['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($item['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div>
                        <label for="semestre_id" class="label-field">Semestre <span class="text-red-500">*</span></label>
                        <select id="semestre_id" name="semestre_id" required class="input-field">
                            <option value="">Seleccione...</option>
                            <?php if (isset($catalogos['semestres'])): ?>
                                <?php foreach ($catalogos['semestres'] as $item): ?>
                                    <option value="<?php echo $item['id']; ?>"
                                        <?php echo (isset($old['semestre_id']) && $old['semestre_id'] == $item['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($item['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div>
                        <label class="label-field">¿Estudió en FyA?</label>
                        <div class="flex gap-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="estudio_fya" value="1"
                                    <?php echo (isset($old['estudio_fya']) && $old['estudio_fya'] == '1') ? 'checked' : ''; ?>
                                    class="mr-2">
                                Sí
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="estudio_fya" value="0"
                                    <?php echo (!isset($old['estudio_fya']) || $old['estudio_fya'] == '0') ? 'checked' : ''; ?>
                                    class="mr-2">
                                No
                            </label>
                        </div>
                    </div>

                    <div>
                        <label for="tipo_beca_id" class="label-field">Tipo de Beca</label>
                        <select id="tipo_beca_id" name="tipo_beca_id" class="input-field">
                            <option value="">Ninguna</option>
                            <?php if (isset($catalogos['tipos_beca'])): ?>
                                <?php foreach ($catalogos['tipos_beca'] as $item): ?>
                                    <option value="<?php echo $item['id']; ?>"
                                        <?php echo (isset($old['tipo_beca_id']) && $old['tipo_beca_id'] == $item['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($item['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Continúa en el siguiente archivo... -->
            <!-- Por ahora incluyo el botón de envío -->

            <div class="flex justify-end gap-4">
                <a href="/" class="inline-block bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-md transition duration-200">
                    Cancelar
                </a>
                <button type="submit" class="btn-primary">
                    Enviar Formulario
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Mostrar/ocultar número de hijos
    document.querySelectorAll('input[name="hijos"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const container = document.getElementById('numero_hijos_container');
            if (this.value === '1') {
                container.style.display = 'block';
            } else {
                container.style.display = 'none';
            }
        });
    });

    // Inicializar al cargar
    if (document.querySelector('input[name="hijos"]:checked')?.value === '1') {
        document.getElementById('numero_hijos_container').style.display = 'block';
    }
</script>