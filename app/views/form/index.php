<main class="container mx-auto px-4 py-8">
    <aside class="absolute top-4 right-4 z-50">
        <?php include __DIR__ . '/../components/theme-toggle.php'; ?>
    </aside>

    <main class="max-w-4xl mx-auto">
        <!-- Header -->
        <header class="card mb-6 flex items-center justify-between p-6 sm:flex-row sm:items-center flex-col gap-4">
            <img class="lg:h-20 md:h-16 h-14 w-auto" src="<?php echo BASE_URL; ?>/assets/iujo.png" alt="IUJO Logo">
            <article class="sm:text-start text-center">
                <h1 class="text-xl md:text-2xl lg:text-3xl font-bold text-gray-800 mb-2">Formulario Socioeconómico</h1>
                <p class="text-gray-600 text-center lg:text-lg md:text-base text-sm">IUJO - Sistema de Registro</p>
            </article>

        </header>

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

        <!-- Progress Bar -->
        <section class="mb-8 relative">
            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200">
                <div id="progressBar" style="width: 16.66%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-primary2-500 transition-all duration-500"></div>
            </div>
            <div class="flex justify-between text-xs text-gray-500">
                <span>Personal</span>
                <span>Académico</span>
                <span>Laboral</span>
                <span>Vivienda</span>
                <span>Familiar</span>
                <span>Económico</span>
            </div>
        </section>

        <?php $actionUrl = !empty($sede) ? BASE_URL . '/' . $sede . '/formulario/submit' : BASE_URL . '/submit'; ?>
        <form id="socioeconomicForm" action="<?php echo $actionUrl; ?>" method="POST" class="space-y-6" enctype="multipart/form-data" data-api-base-url="<?php echo htmlspecialchars(API_BASE_URL); ?>">

            <!-- SECCIÓN 1: DATOS PERSONALES -->
            <section id="step-1" class="form-step hidden">
                <?php include __DIR__ . '/partials/_datos_personales.php'; ?>
                <div class="flex justify-end mt-4">
                    <button type="button" class="btn-primary next-step" data-next="step-2">Siguiente</button>
                </div>
            </section>

            <!-- SECCIÓN 2: DATOS ACADÉMICOS -->
            <section id="step-2" class="form-step">
                <?php include __DIR__ . '/partials/_datos_academicos.php'; ?>
                <div class="flex justify-between mt-4">
                    <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-md transition duration-200 prev-step" data-prev="step-1">Atrás</button>
                    <button type="button" class="btn-primary next-step" data-next="step-3">Siguiente</button>
                </div>
            </section>

            <!-- SECCIÓN 3: DATOS LABORALES -->
            <section id="step-3" class="form-step hidden">
                <?php include __DIR__ . '/partials/_datos_laborales.php'; ?>
                <div class="flex justify-between mt-4">
                    <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-md transition duration-200 prev-step" data-prev="step-2">Atrás</button>
                    <button type="button" class="btn-primary next-step" data-next="step-4">Siguiente</button>
                </div>
            </section>

            <!-- SECCIÓN 4: DATOS DE VIVIENDA -->
            <section id="step-4" class="form-step hidden">
                <?php include __DIR__ . '/partials/_datos_vivienda.php'; ?>
                <div class="flex justify-between mt-4">
                    <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-md transition duration-200 prev-step" data-prev="step-3">Atrás</button>
                    <button type="button" class="btn-primary next-step" data-next="step-5">Siguiente</button>
                </div>
            </section>

            <!-- SECCIÓN 5: DATOS FAMILIARES -->
            <section id="step-5" class="form-step hidden">
                <?php include __DIR__ . '/partials/_datos_familiares.php'; ?>
                <div class="flex justify-between mt-4">
                    <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-md transition duration-200 prev-step" data-prev="step-4">Atrás</button>
                    <button type="button" class="btn-primary next-step" data-next="step-6">Siguiente</button>
                </div>
            </section>

            <!-- SECCIÓN 6: DATOS ECONÓMICOS -->
            <section id="step-6" class="form-step hidden">
                <?php include __DIR__ . '/partials/_datos_economicos.php'; ?>
                <div class="flex justify-between mt-4 gap-4">
                    <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-md transition duration-200 prev-step" data-prev="step-5">Atrás</button>
                    <div class="flex gap-4">
                        <a href="<?php echo BASE_URL; ?>/" class="inline-block bg-gray-400 hover:bg-gray-500 text-white font-semibold py-2 px-6 rounded-md transition duration-200">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-primary">
                            Enviar Formulario
                        </button>
                    </div>
                </div>
            </section>
        </form>
    </main>
</main>

<!-- JavaScript para interactividad -->
<script src="<?php echo BASE_URL; ?>/assets/js/form.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/js/empleo.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/js/familia.js"></script>