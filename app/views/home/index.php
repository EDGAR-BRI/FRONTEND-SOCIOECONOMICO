<main class="container px-4 py-12 min-h-[80vh] bg-gray-100 w-screen  flex flex-col">
    <section class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800 mb-4 tracking-tight">Seleccione su Instituto</h1>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto">Bienvenido al sistema de registro socioeconómico. Por favor, seleccione la sede a la que pertenece para continuar.</p>
    </section>

    <section class="grid grid-col-1 grid-rows-1 lg:grid-cols-3 lg:grid-rows-3 gap-6 p-6 justify-center ">
        <a href="<?php echo BASE_URL; ?>/IUJO-barquisimeto/formulario" class= "lg:col-span-2 card-grit-home ">
            <img src="<?php echo BASE_URL; ?>/assets/iujo.png" alt="IUJO Logo">
            <h2 class =" text-card" >IUJO Barquisimeto</h2>
        </a>
        <a href="<?php echo BASE_URL; ?>/IUJO-Petare/formulario" class= "card-grit-home  lg:row-span-2">
            <img src="<?php echo BASE_URL; ?>/assets/iujo.png" alt="IUJO Logo">
            <h2 class =" text-card">IUJO Petare</h2>
        </a>
        <a href="<?php echo BASE_URL; ?>/IUJO-Catia/formulario" class= "card-grit-home  lg:row-span-2">
            <img src="<?php echo BASE_URL; ?>/assets/iujo.png" alt="IUJO Logo">
            <h2 class =" text-card">IUJO Catia</h2>
        </a>
        <a href="<?php echo BASE_URL; ?>/IUJO-Guanarito/formulario" class = "card-grit-home ">
            <img src="<?php echo BASE_URL; ?>/assets/iujo.png" alt="IUJO Logo">
            <h2 class =" text-card">IUJO Guanarito</h2>
        </a>
        <a href="<?php echo BASE_URL; ?>/IUSF/formulario" class= " card-grit-home  lg:col-span-2">
            <img src="<?php echo BASE_URL; ?>/assets/iujo.png" alt="IUJO Logo">
            <h2 class =" text-card">IUSF</h2>
        </a>

    </section>

    <!-- Bento Grid Container -->
    <div class="w-full max-w-6xl  grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 auto-rows-[280px] hidden">
            <?php foreach ($sedes as $index => $sede): ?>
                <?php 
                    // Lógica visual para Bento Grid (hacer que algunas tarjetas ocupen más espacio)
                    $colSpan = '';
                    $rowSpan = '';
                    
                    if ($index === 0) {
                        $colSpan = 'md:col-span-2 lg:col-span-2'; // Ocupa 2 columnas en pantallas medianas/grandes
                    } elseif ($index === 4) {
                        $colSpan = 'md:col-span-2 lg:col-span-2'; // La última también puede ocupar más si sobra espacio
                    }
                ?>
                <a href="<?php echo BASE_URL . '/' . $sede['id'] . '/formulario'; ?>" 
                class="group relative overflow-hidden rounded-3xl bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] flex flex-col items-center justify-center p-8 border border-gray-100 <?php echo $colSpan . ' ' . $rowSpan; ?>">
                    
                    <!-- Gradiente de fondo en hover -->
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 to-indigo-50/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    
                    <div class="relative z-10 flex flex-col items-center justify-between h-full gap-4 w-full">
                        <div class="flex-1 flex items-center justify-center w-full">
                            <img src="<?php echo BASE_URL; ?>/assets/<?php echo $sede['logo']; ?>" 
                                alt="Logo <?php echo htmlspecialchars($sede['nombre']); ?>" 
                                class="max-h-32 max-w-[80%] drop-shadow-sm group-hover:scale-110 transition-transform duration-500 ease-in-out object-contain">
                        </div>
                        
                        <div class="w-full flex items-center justify-between border-t border-gray-100 pt-4 mt-auto">
                            <h2 class="text-xl font-bold text-gray-800 group-hover:text-blue-600 transition-colors duration-300">
                                <?php echo htmlspecialchars($sede['nombre']); ?>
                            </h2>
                            <div class="h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center group-hover:bg-blue-600 transition-colors duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 group-hover:text-white transition-colors duration-300" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ribete superior interactivo -->
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </a>
            <?php endforeach; ?>
    </div>
                </main>
