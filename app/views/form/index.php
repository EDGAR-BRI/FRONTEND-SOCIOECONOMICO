<main class="container mx-auto px-4 py-8">
    <aside class="absolute top-4 right-4 z-50">
        <label class="inline-flex items-center relative cursor-pointer shrink-0" for="themeToggle" aria-label="Cambiar entre modo claro y oscuro">
            <input class="peer hidden" id="themeToggle" type="checkbox" />
            <div
                class="relative w-[52px] h-[28px] bg-slate-200 peer-checked:bg-zinc-500 rounded-full after:absolute after:content-[''] after:w-[22px] after:h-[22px] after:bg-gradient-to-r from-orange-500 to-yellow-400 peer-checked:after:from-zinc-900 peer-checked:after:to-zinc-900 after:rounded-full after:top-[3px] after:left-[3px] active:after:w-[28px] peer-checked:after:left-[49px] peer-checked:after:translate-x-[-100%] shadow-sm duration-300 after:duration-300 after:shadow-md"></div>
            <svg
                height="0"
                width="100"
                viewBox="0 0 24 24"
                data-name="Layer 1"
                xmlns="http://www.w3.org/2000/svg"
                class="fill-white peer-checked:opacity-60 absolute w-3.5 h-3.5 left-[6px]">
                <path
                    d="M12,17c-2.76,0-5-2.24-5-5s2.24-5,5-5,5,2.24,5,5-2.24,5-5,5ZM13,0h-2V5h2V0Zm0,19h-2v5h2v-5ZM5,11H0v2H5v-2Zm19,0h-5v2h5v-2Zm-2.81-6.78l-1.41-1.41-3.54,3.54,1.41,1.41,3.54-3.54ZM7.76,17.66l-1.41-1.41-3.54,3.54,1.41,1.41,3.54-3.54Zm0-11.31l-3.54-3.54-1.41,1.41,3.54,3.54,1.41-1.41Zm13.44,13.44l-3.54-3.54-1.41,1.41,3.54,3.54,1.41-1.41Z"></path>
            </svg>
            <svg
                height="512"
                width="512"
                viewBox="0 0 24 24"
                data-name="Layer 1"
                xmlns="http://www.w3.org/2000/svg"
                class="fill-black opacity-60 peer-checked:opacity-70 peer-checked:fill-white absolute w-3.5 h-3.5 right-[6px]">
                <path
                    d="M12.009,24A12.067,12.067,0,0,1,.075,10.725,12.121,12.121,0,0,1,10.1.152a13,13,0,0,1,5.03.206,2.5,2.5,0,0,1,1.8,1.8,2.47,2.47,0,0,1-.7,2.425c-4.559,4.168-4.165,10.645.807,14.412h0a2.5,2.5,0,0,1-.7,4.319A13.875,13.875,0,0,1,12.009,24Zm.074-22a10.776,10.776,0,0,0-1.675.127,10.1,10.1,0,0,0-8.344,8.8A9.928,9.928,0,0,0,4.581,18.7a10.473,10.473,0,0,0,11.093,2.734.5.5,0,0,0,.138-.856h0C9.883,16.1,9.417,8.087,14.865,3.124a.459.459,0,0,0,.127-.465.491.491,0,0,0-.356-.362A10.68,10.68,0,0,0,12.083,2ZM20.5,12a1,1,0,0,1-.97-.757l-.358-1.43L17.74,9.428a1,1,0,0,1,.035-1.94l1.4-.325.351-1.406a1,1,0,0,1,1.94,0l.355,1.418,1.418.355a1,1,0,0,1,0,1.94l-1.418.355-.355,1.418A1,1,0,0,1,20.5,12ZM16,14a1,1,0,0,0,2,0A1,1,0,0,0,16,14Zm6,4a1,1,0,0,0,2,0A1,1,0,0,0,22,18Z"></path>
            </svg>
        </label>
    </aside>

    <main class="max-w-4xl mx-auto">
        <!-- Header -->
        <header class="card mb-6 flex items-center justify-between p-6 sm:flex-row sm:items-center flex-col gap-4">
            <img class="h-20 w-auto" src="<?php echo BASE_URL; ?>/assets/iujo.png" alt="IUJO Logo">
            <article class="sm:text-start text-center">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Formulario Socioeconómico</h1>
                <p class="text-gray-600 text-center">IUJO - Sistema de Registro</p>
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