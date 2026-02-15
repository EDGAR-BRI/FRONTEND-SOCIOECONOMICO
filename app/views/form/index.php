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
            <?php include __DIR__ . '/partials/_datos_personales.php'; ?>

            <!-- SECCIÓN 2: DATOS ACADÉMICOS -->
            <?php include __DIR__ . '/partials/_datos_academicos.php'; ?>

            <!-- SECCIÓN 3: DATOS LABORALES -->
            <?php include __DIR__ . '/partials/_datos_laborales.php'; ?>

            <!-- SECCIÓN 4: DATOS DE VIVIENDA -->
            <?php include __DIR__ . '/partials/_datos_vivienda.php'; ?>

            <!-- SECCIÓN 5: DATOS FAMILIARES -->
            <?php include __DIR__ . '/partials/_datos_familiares.php'; ?>

            <!-- SECCIÓN 6: DATOS ECONÓMICOS -->
            <?php include __DIR__ . '/partials/_datos_economicos.php'; ?>

            <!-- Botones de Acción -->
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

<!-- JavaScript para interactividad -->
<script src="/FRONTEND-SOCIOECONOMICO/assets/js/form.js"></script>