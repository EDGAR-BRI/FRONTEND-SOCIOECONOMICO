// form.js - Interactividad del formulario socioeconómico

document.addEventListener('DOMContentLoaded', function () {

    const selects = document.querySelectorAll('select');

    selects.forEach(select => {
        select.addEventListener('change', function () {
            if (this.value) {
                this.classList.remove('border-red-500');

                // Deshabilita y oculta la opción "Seleccione..." por defecto
                const defaultOption = this.querySelector('option[value=""]');
                if (defaultOption) {
                    defaultOption.disabled = true;
                    defaultOption.hidden = true;
                }
            }
        });
    });

    // ===== MOSTRAR/OCULTAR NÚMERO DE HIJOS EN EL FORMULAIRO =====
    const hijosRadios = document.querySelectorAll('input[name="hijos"]');
    const numeroHijosContainer = document.getElementById('numero_hijos_container');

    if (hijosRadios.length > 0 && numeroHijosContainer) {
        hijosRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.value === '1') {
                    numeroHijosContainer.style.display = 'block';
                } else {
                    numeroHijosContainer.style.display = 'none';
                    document.getElementById('numero_hijos').value = '0';
                }
            });
        });

        // Inicializar al cargar
        const hijosChecked = document.querySelector('input[name="hijos"]:checked');
        if (hijosChecked && hijosChecked.value === '1') {
            numeroHijosContainer.style.display = 'block';
        }
    }

    // ===== VALIDACIÓN DE ARCHIVO DE CÉDULA =====
    const cedulaFile = document.getElementById('cedula_file');
    if (cedulaFile) {
        cedulaFile.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                // Validar tamaño (5MB máximo)
                const maxSize = 5 * 1024 * 1024; // 5MB en bytes
                if (file.size > maxSize) {
                    alert('El archivo es demasiado grande. El tamaño máximo es 5MB.');
                    this.value = '';
                    return;
                }

                // Validar tipo de archivo
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Tipo de archivo no permitido. Solo se aceptan JPG, PNG y PDF.');
                    this.value = '';
                    return;
                }
            }
        });
    }

    // ===== SMOOTH SCROLL PARA ERRORES =====
    const errorAlert = document.querySelector('.bg-red-100');
    if (errorAlert) {
        errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // ===== CONFIRMACIÓN ANTES DE ENVIAR =====
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function (e) {
            const veracidad = document.getElementById('veracidad_id');
            if (veracidad && !veracidad.value) {
                e.preventDefault();
                alert('Por favor, confirme la veracidad de la información antes de enviar.');
                veracidad.focus();
                return false;
            }

            // Confirmación final
            const confirmacion = confirm('¿Está seguro de que desea enviar el formulario? Verifique que todos los datos sean correctos.');
            if (!confirmacion) {
                e.preventDefault();
                return false;
            }
        });
    }

    // ===== GESTIÓN DE PASOS DEL FORMULARIO =====
    const steps = document.querySelectorAll('.form-step');
    const progressBar = document.getElementById('progressBar');

    // Función para mostrar un paso específico y ocultar los demás
    function showStep(stepId) {
        steps.forEach(step => {
            if (step.id === stepId) {
                step.classList.remove('hidden');
            } else {
                step.classList.add('hidden');
            }
        });

        // Actualizar barra de progreso
        const stepNumber = parseInt(stepId.replace('step-', ''));
        const totalSteps = steps.length;
        const progress = (stepNumber / totalSteps) * 100;

        if (progressBar) {
            progressBar.style.width = `${progress}%`;
            // progressBar.innerText = `Paso ${stepNumber} de ${totalSteps}`;
        }

        // Scroll arriba
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Función para validar campos del paso actual
    function validateStep(stepElement) {
        // Seleccionamos solo los inputs visibles y habilitados para validar
        const inputs = stepElement.querySelectorAll('input, select, textarea');
        let isValid = true;
        let firstInvalidInput = null;

        // Validación especial: teléfono puede ser 11 dígitos completo o 7 dígitos + prefijo.
        const telefonoEl = stepElement.querySelector('#telefono');
        const prefijoEl = stepElement.querySelector('#prefijo');
        if (telefonoEl) {
            const telefonoVal = (telefonoEl.value || '').replace(/[^0-9]/g, '');
            const prefijoVal = prefijoEl ? (prefijoEl.value || '').replace(/[^0-9]/g, '') : '';

            if (telefonoVal.length === 7 && prefijoVal.length !== 4) {
                telefonoEl.setCustomValidity('Seleccione un prefijo (0414/0416/0424/0426) o escriba el teléfono completo de 11 dígitos.');
            } else {
                telefonoEl.setCustomValidity('');
            }
        }

        inputs.forEach(input => {
            // Check validity solo devuelve false si tiene restricciones (required, pattern, etc) que no se cumplen
            if (!input.checkValidity()) {
                isValid = false;
                if (!firstInvalidInput) firstInvalidInput = input;

                // Resaltar visualmente
                input.classList.add('border-red-500');
                // input.classList.add('ring-2', 'ring-red-500'); // Optional: more visibility
            } else {
                input.classList.remove('border-red-500');
                // input.classList.remove('ring-2', 'ring-red-500');
            }
        });

        if (firstInvalidInput) {
            firstInvalidInput.reportValidity(); // Muestra el mensaje nativo solo del primero
            firstInvalidInput.focus();
        }

        return isValid;
    }

    // Inicializar visualización de pasos
    // Ocultar todos menos el primero si no se ha hecho ya
    steps.forEach((step, index) => {
        if (index === 0) { // Índice 0 corresponde a la sección 1
            step.classList.remove('hidden');
        } else {
            step.classList.add('hidden');
        }
    });

    // Actualizar barra de progreso para iniciar en el paso 1
    if (progressBar && steps.length > 0) {
        progressBar.style.width = `${(1 / steps.length) * 100}%`;
    }

    // Event Listeners para botones Siguiente
    document.querySelectorAll('.next-step').forEach(button => {
        button.addEventListener('click', async function () {
            // Buscamos el contenedor del paso actual
            // Usamos closest('.form-step') para asegurarnos de obtener el padre correcto
            const currentStep = this.closest('.form-step');
            const nextStepId = this.dataset.next;

            if (!currentStep || !validateStep(currentStep)) {
                return;
            }

            // Chequeo temprano: en el paso 1 validar duplicados de cédula/email.
            if (currentStep.id === 'step-1') {
                const formEl = document.getElementById('socioeconomicForm');
                const apiBaseUrl = formEl ? (formEl.dataset.apiBaseUrl || '').replace(/\/+$/, '') : '';
                const cedulaEl = currentStep.querySelector('#cedula');
                const emailEl = currentStep.querySelector('#email');

                if (apiBaseUrl && (cedulaEl || emailEl)) {
                    const cedula = cedulaEl ? (cedulaEl.value || '').trim() : '';
                    const email = emailEl ? (emailEl.value || '').trim() : '';

                    // Solo consultar si hay algo que verificar
                    if (cedula !== '' || email !== '') {
                        // Evitar doble click mientras consulta
                        const prevDisabled = this.disabled;
                        this.disabled = true;

                        try {
                            const qs = new URLSearchParams();
                            if (cedula !== '') qs.set('cedula', cedula);
                            if (email !== '') qs.set('email', email);

                            const resp = await fetch(apiBaseUrl + '/encuesta/check?' + qs.toString(), {
                                method: 'GET',
                                headers: { 'Accept': 'application/json' },
                            });

                            const payload = await resp.json().catch(() => null);

                            // Resetear mensajes previos
                            if (cedulaEl) cedulaEl.setCustomValidity('');
                            if (emailEl) emailEl.setCustomValidity('');

                            if (!resp.ok || !payload || payload.success !== true || !payload.data) {
                                // Si falla el check, dejamos continuar (para no bloquear por un fallo de red).
                                // El backend definitivo validará al enviar.
                                showStep(nextStepId);
                                return;
                            }

                            const cedulaExists = !!payload.data.cedula_exists;
                            const emailExists = !!payload.data.email_exists;

                            if (cedulaExists) {
                                cedulaEl.setCustomValidity('Ya existe una encuesta registrada con esta cédula.');
                            }
                            if (emailExists) {
                                emailEl.setCustomValidity('Ya existe una encuesta registrada con este correo.');
                            }

                            if (cedulaExists) {
                                cedulaEl.reportValidity();
                                cedulaEl.focus();
                                return;
                            }

                            if (emailExists) {
                                emailEl.reportValidity();
                                emailEl.focus();
                                return;
                            }

                            showStep(nextStepId);
                            return;
                        } catch (e) {
                            // En caso de error de red/parseo, no bloqueamos el avance.
                            showStep(nextStepId);
                            return;
                        } finally {
                            this.disabled = prevDisabled;
                        }
                    }
                }
            }

            showStep(nextStepId);
        });
    });

    // Event Listeners para botones Atrás
    document.querySelectorAll('.prev-step').forEach(button => {

        button.addEventListener('click', function () {
            const prevStepId = this.dataset.prev;
            showStep(prevStepId);
        });
    });

    // Limpiar validaciones personalizadas (duplicados) al editar
    const cedulaInput = document.getElementById('cedula');
    if (cedulaInput) {
        cedulaInput.addEventListener('input', function () {
            this.setCustomValidity('');
        });
    }

    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('input', function () {
            this.setCustomValidity('');
        });
    }
});
