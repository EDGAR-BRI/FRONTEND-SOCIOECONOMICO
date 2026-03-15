<div class="bg-white rounded-lg shadow-sm border p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Detalle de Encuesta</h3>
            <?php if (isset($encuesta) && is_array($encuesta) && !empty($encuesta)): ?>
                <?php
                    $nombres = isset($encuesta['nombres']) ? trim((string)$encuesta['nombres']) : '';
                    $apellidos = isset($encuesta['apellidos']) ? trim((string)$encuesta['apellidos']) : '';
                    $cedula = isset($encuesta['cedula']) ? trim((string)$encuesta['cedula']) : '';

                    $nombreCompleto = trim($nombres . ' ' . $apellidos);
                    $sub = [];
                    if ($nombreCompleto !== '') {
                        $sub[] = $nombreCompleto;
                    }
                    if ($cedula !== '') {
                        $sub[] = 'C.I. ' . $cedula;
                    }
                ?>
                <?php if (!empty($sub)): ?>
                    <p class="text-sm text-gray-500 mt-1"><?php echo htmlspecialchars(implode(' · ', $sub)); ?></p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <a href="<?php echo BASE_URL; ?>/admin/respuestas" class="px-3 py-2 border rounded text-sm text-gray-700 hover:bg-gray-50">Volver</a>
    </div>

    <?php if (isset($apiError) && is_array($apiError) && !empty($apiError['message'])): ?>
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <?php echo htmlspecialchars((string)$apiError['message']); ?>
            <?php if (!empty($apiError['status']) && (int)$apiError['status'] === 401): ?>
                <span class="ml-2">Vuelve a iniciar sesión.</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (!isset($encuesta) || !is_array($encuesta) || empty($encuesta)): ?>
        <div class="text-sm text-gray-600">No se pudo cargar el detalle de la encuesta.</div>
    <?php else: ?>

        <?php
            $activos = (isset($encuesta['activos_vivienda']) && is_array($encuesta['activos_vivienda'])) ? $encuesta['activos_vivienda'] : [];
            $ambientes = (isset($encuesta['ambientes_vivienda']) && is_array($encuesta['ambientes_vivienda'])) ? $encuesta['ambientes_vivienda'] : [];
            $servicios = (isset($encuesta['servicios_vivienda']) && is_array($encuesta['servicios_vivienda'])) ? $encuesta['servicios_vivienda'] : [];

            $getValue = function ($primaryKey, $fallbackKey = null) use ($encuesta) {
                if (is_string($primaryKey) && array_key_exists($primaryKey, $encuesta)) {
                    return $encuesta[$primaryKey];
                }
                if ($fallbackKey !== null && is_string($fallbackKey) && array_key_exists($fallbackKey, $encuesta)) {
                    return $encuesta[$fallbackKey];
                }
                return null;
            };

            $renderScalar = function ($value, $multiline = false) {
                if ($value === null) {
                    return '<span class="text-gray-400">-</span>';
                }

                if (is_bool($value)) {
                    $value = $value ? '1' : '0';
                }

                if (is_array($value)) {
                    return '<span class="text-gray-400">-</span>';
                }

                $str = trim((string)$value);
                if ($str === '') {
                    return '<span class="text-gray-400">-</span>';
                }

                return $multiline ? nl2br(htmlspecialchars($str)) : htmlspecialchars($str);
            };

            $renderYesNo = function ($value) {
                if ($value === null || $value === '') {
                    return '<span class="text-gray-400">-</span>';
                }

                if ($value === true || $value === 1 || $value === '1') {
                    return 'Sí';
                }
                if ($value === false || $value === 0 || $value === '0') {
                    return 'No';
                }

                $str = trim((string)$value);
                if ($str === '') {
                    return '<span class="text-gray-400">-</span>';
                }
                return htmlspecialchars($str);
            };

            $renderBox = function ($innerHtml) {
                return '<div class="input-field bg-gray-50 text-gray-700">' . $innerHtml . '</div>';
            };

            $renderChips = function (array $items) {
                if (empty($items)) {
                    return '<div class="text-sm text-gray-500">-</div>';
                }

                $html = '<div class="flex flex-wrap gap-2">';
                foreach ($items as $item) {
                    $name = is_array($item) ? (string)($item['nombre'] ?? '') : (string)$item;
                    $name = trim($name);
                    if ($name === '') {
                        continue;
                    }
                    $html .= '<span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-medium border border-gray-200">' . htmlspecialchars($name) . '</span>';
                }
                $html .= '</div>';

                return $html;
            };
        ?>

        <div class="border rounded p-4 mb-6">
            <div class="text-xs uppercase tracking-wide text-gray-500">Resumen</div>
            <div class="text-sm text-gray-700 mt-1">
                Instituto: <?php
                    $inst = '';
                    if (!empty($encuesta['instituto_siglas'])) {
                        $inst = (string)$encuesta['instituto_siglas'];
                    }
                    if (!empty($encuesta['instituto_nombre'])) {
                        $inst = $inst !== '' ? ($inst . ' - ' . (string)$encuesta['instituto_nombre']) : (string)$encuesta['instituto_nombre'];
                    }
                    echo $inst !== '' ? htmlspecialchars($inst) : '-';
                ?>
            </div>
            <div class="text-sm text-gray-700 mt-1">
                Fecha: <?php echo !empty($encuesta['creado']) ? htmlspecialchars((string)$encuesta['creado']) : '-'; ?>
            </div>
            <div class="text-sm text-gray-700 mt-1">
                Estrato: <?php
                    if (array_key_exists('estrato', $encuesta) && $encuesta['estrato'] !== null && $encuesta['estrato'] !== '') {
                        echo '<span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium border border-green-200">' . htmlspecialchars((string)$encuesta['estrato']) . '</span>';
                    } else {
                        echo '<span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-medium border border-gray-200">Pendiente</span>';
                    }
                ?>
            </div>
        </div>

        <div class="space-y-6">
            <section class="border rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b">1. Datos Personales</h2>

                <article class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label-field">Nombres <span class="text-red-500">*</span></label>
                        <?php echo $renderBox($renderScalar($getValue('nombres'))); ?>
                    </div>

                    <div>
                        <label class="label-field">Apellidos <span class="text-red-500">*</span></label>
                        <?php echo $renderBox($renderScalar($getValue('apellidos'))); ?>
                    </div>

                    <div>
                        <label class="label-field">Cédula <span class="text-red-500">*</span></label>
                        <?php echo $renderBox($renderScalar($getValue('cedula'))); ?>
                    </div>

                    <div>
                        <label class="label-field">Nacionalidad <span class="text-red-500">*</span></label>
                        <?php
                            $nac = $getValue('nacionalidad');
                            if ($nac === null || $nac === '') {
                                $nac = $getValue('nacionalidad_id');
                            }
                            echo $renderBox($renderScalar($nac));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">Sexo <span class="text-red-500">*</span></label>
                        <?php
                            $sexo = $getValue('sexo');
                            if ($sexo === null || $sexo === '') {
                                $sexo = $getValue('sexo_id');
                            }
                            echo $renderBox($renderScalar($sexo));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">Fecha de Nacimiento <span class="text-red-500">*</span></label>
                        <?php echo $renderBox($renderScalar($getValue('fecha_nacimiento'))); ?>
                    </div>

                    <div>
                        <label class="label-field">Correo Electrónico <span class="text-red-500">*</span></label>
                        <?php echo $renderBox($renderScalar($getValue('email'))); ?>
                    </div>

                    <div>
                        <label class="label-field">Teléfono <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="col-span-1">
                                <?php echo $renderBox($renderScalar($getValue('prefijo'))); ?>
                            </div>
                            <div class="col-span-2">
                                <?php echo $renderBox($renderScalar($getValue('telefono'))); ?>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="label-field">Estado Civil <span class="text-red-500">*</span></label>
                        <?php
                            $ec = $getValue('estado_civil');
                            if ($ec === null || $ec === '') {
                                $ec = $getValue('estado_civil_id');
                            }
                            echo $renderBox($renderScalar($ec));
                        ?>
                    </div>

                    <div class="md:col-span-2">
                        <label class="label-field">Dirección <span class="text-red-500">*</span></label>
                        <?php echo $renderBox($renderScalar($getValue('direccion'), true)); ?>
                    </div>

                    <div>
                        <label class="label-field">Discapacidad (si aplica)</label>
                        <?php echo $renderBox($renderScalar($getValue('discapacidad'))); ?>
                    </div>

                    <div>
                        <label class="label-field">Enfermedad Crónica (si aplica)</label>
                        <?php echo $renderBox($renderScalar($getValue('enfermedad_cronica'))); ?>
                    </div>

                    <div>
                        <label class="label-field">¿Tiene hijos?</label>
                        <?php echo $renderBox($renderYesNo($getValue('hijos'))); ?>
                    </div>

                    <?php $tieneHijos = ($getValue('hijos') === 1 || $getValue('hijos') === '1' || $getValue('hijos') === true); ?>
                    <?php if ($tieneHijos): ?>
                        <div>
                            <label class="label-field">Número de Hijos</label>
                            <?php echo $renderBox($renderScalar($getValue('numero_hijos'))); ?>
                        </div>
                    <?php endif; ?>
                </article>
            </section>

            <section class="border rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b">2. Datos Académicos</h2>

                <article class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label-field">Tipo de Estudiante <span class="text-red-500">*</span></label>
                        <?php
                            $te = $getValue('tipo_estudiante');
                            if ($te === null || $te === '') {
                                $te = $getValue('tipo_estudiante_id');
                            }
                            echo $renderBox($renderScalar($te));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">Carrera <span class="text-red-500">*</span></label>
                        <?php
                            $car = $getValue('carrera');
                            if ($car === null || $car === '') {
                                $car = $getValue('carrera_id');
                            }
                            echo $renderBox($renderScalar($car));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">Semestre <span class="text-red-500">*</span></label>
                        <?php
                            $sem = $getValue('semestre');
                            if ($sem === null || $sem === '') {
                                $sem = $getValue('semestre_id');
                            }
                            echo $renderBox($renderScalar($sem));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">Tipo de Beca</label>
                        <?php
                            $tb = $getValue('tipo_beca');
                            if ($tb === null || $tb === '') {
                                $tb = $getValue('tipo_beca_id');
                            }
                            echo $renderBox($renderScalar($tb));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">¿Estudió en FyA?</label>
                        <?php echo $renderBox($renderYesNo($getValue('estudio_fya'))); ?>
                    </div>
                </article>
            </section>

            <section class="border rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b">3. Datos Laborales</h2>

                <article class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label-field">Condición Laboral</label>
                        <?php
                            $cl = $getValue('condicion_laboral');
                            if ($cl === null || $cl === '') {
                                $cl = $getValue('condicion_laboral_id');
                            }
                            echo $renderBox($renderScalar($cl));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">Relación Laboral</label>
                        <?php
                            $rl = $getValue('relacion_laboral');
                            if ($rl === null || $rl === '') {
                                $rl = $getValue('trabajo_relacion_id', 'relacion_laboral_id');
                            }
                            echo $renderBox($renderScalar($rl));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">Tipo de Organización</label>
                        <?php
                            $to = $getValue('tipo_organizacion');
                            if ($to === null || $to === '') {
                                $to = $getValue('tipo_organizacion_id');
                            }
                            echo $renderBox($renderScalar($to));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">Sector de Trabajo</label>
                        <?php
                            $st = $getValue('sector_trabajo');
                            if ($st === null || $st === '') {
                                $st = $getValue('sector_trabajo_id');
                            }
                            echo $renderBox($renderScalar($st));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">Categoría Ocupacional</label>
                        <?php
                            $co = $getValue('categoria_ocupacional');
                            if ($co === null || $co === '') {
                                $co = $getValue('categoria_ocupacional_id');
                            }
                            echo $renderBox($renderScalar($co));
                        ?>
                    </div>
                </article>
            </section>

            <section class="border rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b">4. Datos de Vivienda</h2>

                <article class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label-field">Tipo de Convivencia</label>
                        <?php
                            $tc = $getValue('tipo_convivencia');
                            if ($tc === null || $tc === '') {
                                $tc = $getValue('tipo_convivencia_id');
                            }
                            echo $renderBox($renderScalar($tc));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">Tipo de Vivienda</label>
                        <?php
                            $tv = $getValue('tipo_vivienda');
                            if ($tv === null || $tv === '') {
                                $tv = $getValue('tipo_vivienda_id');
                            }
                            echo $renderBox($renderScalar($tv));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">Tenencia de Vivienda</label>
                        <?php
                            $tnv = $getValue('tenencia_vivienda');
                            if ($tnv === null || $tnv === '') {
                                $tnv = $getValue('tenencia_vivienda_id');
                            }
                            echo $renderBox($renderScalar($tnv));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">Número de Habitantes</label>
                        <?php echo $renderBox($renderScalar($getValue('numero_habitantes'))); ?>
                    </div>

                    <div>
                        <label class="label-field">Número de Ocupantes de la Familia</label>
                        <?php echo $renderBox($renderScalar($getValue('numero_ocupantes_familia'))); ?>
                    </div>

                    <div class="md:col-span-2">
                        <label class="label-field">Ambientes de la Vivienda</label>
                        <?php echo $renderChips($ambientes); ?>
                    </div>

                    <div class="md:col-span-2">
                        <label class="label-field">Activos de la Vivienda</label>
                        <?php echo $renderChips($activos); ?>
                    </div>

                    <div class="md:col-span-2">
                        <label class="label-field">Servicios de la Vivienda</label>
                        <?php echo $renderChips($servicios); ?>
                    </div>

                    <div>
                        <label class="label-field">Frecuencia Servicio de Agua</label>
                        <?php
                            $fa = $getValue('frecuencia_servicio_agua');
                            if ($fa === null || $fa === '') {
                                $fa = $getValue('frecuencia_servicio_agua_id', 'frecuencia_agua_id');
                            }
                            echo $renderBox($renderScalar($fa));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">Frecuencia Servicio de Aseo</label>
                        <?php
                            $fas = $getValue('frecuencia_servicio_aseo');
                            if ($fas === null || $fas === '') {
                                $fas = $getValue('frecuencia_servicio_aseo_id', 'frecuencia_aseo_id');
                            }
                            echo $renderBox($renderScalar($fas));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">Frecuencia Servicio de Electricidad</label>
                        <?php
                            $fe = $getValue('frecuencia_servicio_electricidad');
                            if ($fe === null || $fe === '') {
                                $fe = $getValue('frecuencia_servicio_electricidad_id', 'frecuencia_electricidad_id');
                            }
                            echo $renderBox($renderScalar($fe));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">Frecuencia Servicio de Gas</label>
                        <?php
                            $fg = $getValue('frecuencia_servicio_gas');
                            if ($fg === null || $fg === '') {
                                $fg = $getValue('frecuencia_servicio_gas_id', 'frecuencia_gas_id');
                            }
                            echo $renderBox($renderScalar($fg));
                        ?>
                    </div>
                </article>
            </section>

            <section class="border rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b">5. Datos Familiares</h2>

                <h3 class="text-xl font-semibold text-gray-700 mb-3 mt-4">Datos del Padre</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="label-field">Nivel de Educación</label>
                        <?php
                            $nep = $getValue('nivel_eduacion_padre');
                            if ($nep === null || $nep === '') {
                                $nep = $getValue('nivel_eduacion_padre_id', 'nivel_educacion_padre_id');
                            }
                            echo $renderBox($renderScalar($nep));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">¿Trabaja?</label>
                        <?php echo $renderBox($renderYesNo($getValue('trabaja_padre', 'padre_trabaja'))); ?>
                    </div>

                    <div>
                        <label class="label-field">Tipo de Empresa</label>
                        <?php
                            $tep = $getValue('tipo_empresa_padre');
                            if ($tep === null || $tep === '') {
                                $tep = $getValue('tipo_empresa_padre_id');
                            }
                            echo $renderBox($renderScalar($tep));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">Categoría Ocupacional</label>
                        <?php
                            $cop = $getValue('categoria_ocupacional_padre');
                            if ($cop === null || $cop === '') {
                                $cop = $getValue('categoria_ocupacional_padre_id');
                            }
                            echo $renderBox($renderScalar($cop));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">Sector de Trabajo</label>
                        <?php
                            $stp = $getValue('sector_trabajo_padre');
                            if ($stp === null || $stp === '') {
                                $stp = $getValue('sector_trabajo_padre_id');
                            }
                            echo $renderBox($renderScalar($stp));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">¿Está en Venezuela?</label>
                        <?php echo $renderBox($renderYesNo($getValue('padre_en_venezuela'))); ?>
                    </div>

                    <div>
                        <label class="label-field">¿Es egresado del IUJO?</label>
                        <?php echo $renderBox($renderYesNo($getValue('padre_egresado_iujo'))); ?>
                    </div>
                </div>

                <h3 class="text-xl font-semibold text-gray-700 mb-3 mt-6">Datos de la Madre</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label-field">Nivel de Educación</label>
                        <?php
                            $nem = $getValue('nivel_eduacion_madre');
                            if ($nem === null || $nem === '') {
                                $nem = $getValue('nivel_eduacion_madre_id', 'nivel_educacion_madre_id');
                            }
                            echo $renderBox($renderScalar($nem));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">¿Trabaja?</label>
                        <?php echo $renderBox($renderYesNo($getValue('trabaja_madre', 'madre_trabaja'))); ?>
                    </div>

                    <div>
                        <label class="label-field">Tipo de Empresa</label>
                        <?php
                            $tem = $getValue('tipo_empresa_madre');
                            if ($tem === null || $tem === '') {
                                $tem = $getValue('tipo_empresa_madre_id');
                            }
                            echo $renderBox($renderScalar($tem));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">Categoría Ocupacional</label>
                        <?php
                            $com = $getValue('categoria_ocupacional_madre');
                            if ($com === null || $com === '') {
                                $com = $getValue('categoria_ocupacional_madre_id');
                            }
                            echo $renderBox($renderScalar($com));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">Sector de Trabajo</label>
                        <?php
                            $stm = $getValue('sector_trabajo_madre');
                            if ($stm === null || $stm === '') {
                                $stm = $getValue('sector_trabajo_madre_id');
                            }
                            echo $renderBox($renderScalar($stm));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">¿Está en Venezuela?</label>
                        <?php echo $renderBox($renderYesNo($getValue('madre_en_venezuela'))); ?>
                    </div>

                    <div>
                        <label class="label-field">¿Es egresada del IUJO?</label>
                        <?php echo $renderBox($renderYesNo($getValue('madre_egresada_iujo'))); ?>
                    </div>
                </div>
            </section>

            <section class="border rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b">6. Datos Económicos</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label-field">Transporte</label>
                        <?php
                            $tr = $getValue('transporte');
                            if ($tr === null || $tr === '') {
                                $tr = $getValue('transporte_id');
                            }
                            echo $renderBox($renderScalar($tr));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">Dependencia Económica</label>
                        <?php
                            $de = $getValue('dependencia_economica');
                            if ($de === null || $de === '') {
                                $de = $getValue('dependencia_economica_id');
                            }
                            echo $renderBox($renderScalar($de));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">Fuente de Ingreso Familiar</label>
                        <?php
                            $fi = $getValue('fuente_ingreso_familiar');
                            if ($fi === null || $fi === '') {
                                $fi = $getValue('fuente_ingreso_familiar_id', 'fuente_ingreso_id');
                            }
                            echo $renderBox($renderScalar($fi));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">Ingreso Familiar</label>
                        <?php
                            $inf = $getValue('ingreso_familiar');
                            if ($inf === null || $inf === '') {
                                $inf = $getValue('ingreso_familiar_id');
                            }
                            echo $renderBox($renderScalar($inf));
                        ?>
                    </div>

                    <div>
                        <label class="label-field">Veracidad de la Información <span class="text-red-500">*</span></label>
                        <?php
                            $v = $getValue('veracidad');
                            if ($v === null || $v === '') {
                                $v = $getValue('veracidad_id');
                            }
                            echo $renderBox($renderScalar($v));
                        ?>
                    </div>

                    <div class="md:col-span-2">
                        <label class="label-field">Cédula de Identidad (Archivo)</label>
                        <?php
                            $urlCedula = $getValue('url_cedula');
                            $urlCedula = is_string($urlCedula) ? trim($urlCedula) : '';
                            if ($urlCedula === '') {
                                echo $renderBox('<span class="text-gray-400">-</span>');
                            } else {
                                $isSafeUrl = false;
                                if (preg_match('/^https?:\/\//i', $urlCedula)) {
                                    $isSafeUrl = true;
                                } elseif (substr($urlCedula, 0, 1) === '/') {
                                    $isSafeUrl = true;
                                }

                                if ($isSafeUrl) {
                                    $href = htmlspecialchars($urlCedula);
                                    echo $renderBox('<a class="text-primary2-600 hover:underline" href="' . $href . '" target="_blank" rel="noopener">Ver archivo</a>');
                                } else {
                                    echo $renderBox($renderScalar($urlCedula));
                                }
                            }
                        ?>
                    </div>
                </div>
            </section>
        </div>

    <?php endif; ?>
</div>
