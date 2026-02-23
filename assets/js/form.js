// form.js - Interactividad del formulario socioeconómico

document.addEventListener('DOMContentLoaded', function() {
    
    // ===== MOSTRAR/OCULTAR NÚMERO DE HIJOS =====
    const hijosRadios = document.querySelectorAll('input[name="hijos"]');
    const numeroHijosContainer = document.getElementById('numero_hijos_container');
    
    if (hijosRadios.length > 0 && numeroHijosContainer) {
        hijosRadios.forEach(radio => {
            radio.addEventListener('change', function() {
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
        cedulaFile.addEventListener('change', function() {
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
        form.addEventListener('submit', function(e) {
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
    // Ocultar todos menos el primero si no se ha hecho ya (por si acaso el backend no lo hizo)
    steps.forEach((step, index) => {
        if (index === 0) {
            step.classList.remove('hidden');
        } else {
            step.classList.add('hidden');
        }
    });

    // Event Listeners para botones Siguiente
    document.querySelectorAll('.next-step').forEach(button => {
        button.addEventListener('click', function() {
            // Buscamos el contenedor del paso actual
            // Usamos closest('.form-step') para asegurarnos de obtener el padre correcto
            const currentStep = this.closest('.form-step');
            const nextStepId = this.dataset.next;
            
            if (currentStep && validateStep(currentStep)) {
                showStep(nextStepId);
            }
        });
    });

    // Event Listeners para botones Atrás
    document.querySelectorAll('.prev-step').forEach(button => {
        // ...

        button.addEventListener('click', function() {
            const prevStepId = this.dataset.prev;
            showStep(prevStepId);
        });
    });
});
