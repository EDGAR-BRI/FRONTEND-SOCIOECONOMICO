  <Header class=" flex items-center backdrop-blur-sm w-full z-50 justify-between gap-4 flex-wrap mb-6 px-8 py-2 rounded-lg  shadow-md">
      <div class="w-full max-w-[10rem] sm:max-w-[12rem] md:max-w-[14rem] shrink-0">
          <?php include APP_PATH . '/views/components/logo.php'; ?>

      </div>
      <h1 class=" text-3xl font-bold">Sistema de registro socioeconómico</h1>
      <div class="flex justify-center items-center gap-4">
          <a href="admin">
              <i class="fa-solid text-xl fa-user-shield"></i>
          </a>
          <?php include __DIR__ . '/../components/theme-toggle.php'; ?>
      </div>

  </Header>

  <main class=" relative px-4 py-12 min-h-[80vh] r-0 l-0 mx-auto flex flex-col">

      <section class="text-center mb-6">
          <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800 mb-4 tracking-tight">Seleccione su Instituto</h1>
          <p class="text-xl text-gray-600 max-w-2xl mx-auto">Bienvenido al sistema de registro socioeconómico. Por favor, seleccione la sede a la que pertenece para continuar.</p>
      </section>

      <section class="grid grid-cols-1 grid-rows-1 lg:grid-cols-3 lg:grid-rows-3 gap-6 py-6 px-2 md:px-6 max-w-450 w-full left-0 right-0 mx-auto  justify-center ">

          <a href="<?php echo BASE_URL; ?>/IUJO-BQTO/formulario" class=" block style-card group lg:col-span-2 card-grit-home relative overflow-hidden min-h-75 @container">
              <div class="container-logo">
                  <img src="<?php echo BASE_URL; ?>/assets/img/iujo-barquisimeto2.jpg" alt="IUJO Barquisimeto" class="@[930px]:w-1/2 w-full  h-full object-cover @[930px]:object-top transition-all duration-500">
                  <img src="<?php echo BASE_URL; ?>/assets/img/IUJO-Barquisimeto-1024x1024.jpg" alt="IUJO Barquisimeto" class="w-0 hidden  @[710px]:block @[930px]:w-1/2 h-full object-cover transition-all duration-500">
                  <span class="style-overlay absolute inset-0" style="background: linear-gradient(to right, rgba(11, 17, 32, 0.70), rgba(18, 27, 46, 0.50));"></span>
              </div>
              <div class="relative z-10 flex flex-col h-full w-full items-center">
                  <div class=" absolute top-4 left-4">
                      <?php $logoColorMode = 'white'; ?>
                      <?php include APP_PATH . '/views/components/logo.php'; ?>
                      <?php unset($logoColorMode); ?>
                  </div>
                  <span class="mt-auto self-end rounded-full bg-black/45 px-3 py-1 text-xl font-semibold tracking-wide text-white backdrop-blur-sm shadow-lg">IUJO Barquisimeto
                  </span>
              </div>
          </a>

          <a href="<?php echo BASE_URL; ?>/IUJO-PETARE/formulario" class="style-card block group card-grit-home lg:row-span-2 relative overflow-hidden min-h-75 @container">
              <div class="container-logo">
                  <img src="<?php echo BASE_URL; ?>/assets/img/iujo-petare2-2024.jpg" alt="IUJO Petare" class="w-full h-full object-cover ">
                  <img src="<?php echo BASE_URL; ?>/assets/img/iujo-petare2-2024.jpg" alt="IUJO Petare" class="block @[500px]:hidden w-full h-full object-cover">
                  <span class="style-overlay absolute inset-0" style="background: linear-gradient(to right, rgba(11, 17, 32, 0.70), rgba(18, 27, 46, 0.50));"></span>
              </div>
              <div class="relative z-10 flex flex-col h-full w-full items-center">
                  <div class=" absolute top-4 left-4">
                      <?php $logoColorMode = 'white'; ?>
                      <?php include APP_PATH . '/views/components/logo.php'; ?>
                      <?php unset($logoColorMode); ?>
                  </div>
                  <h2 class=" mt-auto self-end rounded-full bg-black/45 px-3 py-1 text-xl font-semibold tracking-wide text-white backdrop-blur-sm shadow-lg">IUJO Petare</h2>
              </div>
          </a>

          <a href="<?php echo BASE_URL; ?>/IUJO-CATIA/formulario" class="style-card block group card-grit-home lg:row-span-2 relative overflow-hidden min-h-75 @container">
              <div class="container-logo">
                  <img src="<?php echo BASE_URL; ?>/assets/img/IUJO-Catia-768x768.jpg" alt="IUJO catia" class="w-full h-full object-cover ">
                  <img src="<?php echo BASE_URL; ?>/assets/img/ITJO-2.jpeg" alt="IUJO catia" class="block @[500px]:hidden w-full h-full object-cover">
                  <span class="style-overlay absolute inset-0" style="background: linear-gradient(to right, rgba(11, 17, 32, 0.70), rgba(18, 27, 46, 0.50));"></span>
              </div>

              <div class="relative z-10 flex flex-col h-full w-full items-center">
                  <div class=" absolute top-4 left-4">
                      <?php $logoColorMode = 'white'; ?>
                      <?php include APP_PATH . '/views/components/logo.php'; ?>
                      <?php unset($logoColorMode); ?>
                  </div>
                  <h2 class=" mt-auto self-end rounded-full bg-black/45 px-3 py-1 text-xl font-semibold tracking-wide text-white backdrop-blur-sm shadow-lg">IUJO Catia</h2>
              </div>
          </a>

          <a href="<?php echo BASE_URL; ?>/IUJO-GUANARITO/formulario" class=" block style-card group card-grit-home relative overflow-hidden min-h-75 @container">
              <div class="container-logo">
                  <img src="<?php echo BASE_URL; ?>/assets/img/IUJO-Guanarito.jpg" alt="IUJO Guanarito" class="w-full h-full object-cover">
                  <span class="style-overlay absolute inset-0" style="background: linear-gradient(to right, rgba(11, 17, 32, 0.70), rgba(18, 27, 46, 0.50));"></span>
              </div>
              <div class="relative z-10 flex flex-col h-full w-full items-center">
                  <div class=" absolute top-4 left-4">
                      <?php $logoColorMode = 'white'; ?>
                      <?php $sede = 'IUSF'; ?>
                      <?php include APP_PATH . '/views/components/logo.php'; ?>
                      <?php unset($sede, $logoColorMode); ?>
                  </div>
                  <h2 class=" mt-auto self-end rounded-full bg-black/45 px-3 py-1 text-xl font-semibold tracking-wide text-white backdrop-blur-sm shadow-lg">IUJO Guanarito</h2>
              </div>
          </a>

          <a href="<?php echo BASE_URL; ?>/IUSF/formulario" class=" block style-card group lg:col-span-2 card-grit-home relative overflow-hidden min-h-75 @container">
              <div class="container-logo">
                  <img src="<?php echo BASE_URL; ?>/assets/img/IUSF-1024x1024.jpg" alt="IUJO Barquisimeto" class="@[930px]:w-1/2 w-full  h-full object-cover @[930px]:object-top transition-all duration-500">
                  <img src="<?php echo BASE_URL; ?>/assets/img/iusf2.jpg" alt="IUJO Barquisimeto" class="w-0 hidden  @[710px]:block @[930px]:w-1/2 h-full object-cover transition-all duration-500">
                  <span class="style-overlay absolute inset-0" style="background: linear-gradient(to right, rgba(11, 17, 32, 0.70), rgba(18, 27, 46, 0.50));"></span>
              </div>
              <div class="relative z-10 flex flex-col h-full w-full items-center">
                  <div class=" absolute top-4 left-4">
                      <?php $logoColorMode = 'white'; ?>
                      <?php $sede = 'IUSF'; ?>
                      <?php include APP_PATH . '/views/components/logo.php'; ?>
                      <?php unset($sede, $logoColorMode); ?>
                  </div>
                  <h2 class=" mt-auto self-end rounded-full bg-black/45 px-3 py-1 text-xl font-semibold tracking-wide text-white backdrop-blur-sm shadow-lg">IUSF</h2>
              </div>
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
                  <div class="absolute inset-0 bg-linear-to-br from-blue-50/50 to-indigo-50/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

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
                              <svg class="h-5 w-5 text-blue-600 group-hover:text-white transition-colors duration-300" viewBox="0 0 20 20" fill="currentColor">
                                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                              </svg>
                          </div>
                      </div>
                  </div>

                  <!-- Ribete superior interactivo -->
                  <div class="absolute top-0 left-0 w-full h-1 bg-linear-to-r from-blue-400 to-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
              </a>
          <?php endforeach; ?>
      </div>
  </main>