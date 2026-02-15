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

    // ===== INDICADOR DE PROGRESO =====
    const sections = document.querySelectorAll('.card');
    let completedSections = 0;
    
    sections.forEach((section, index) => {
        const inputs = section.querySelectorAll('input[required], select[required], textarea[required]');
        let filledInputs = 0;
        
        inputs.forEach(input => {
            if (input.value && input.value !== '') {
                filledInputs++;
            }
        });
        
        if (filledInputs === inputs.length && inputs.length > 0) {
            completedSections++;
        }
    });

    // Mostrar progreso en consola (opcional)
    console.log(`Progreso del formulario: ${completedSections}/${sections.length} secciones completadas`);
});
