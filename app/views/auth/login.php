<div class="relative min-h-[calc(100vh-4rem)] flex items-center justify-center px-4 py-8 overflow-hidden">
    <div class="absolute top-12 right-20 hidden lg:grid grid-cols-6 gap-4 opacity-45" aria-hidden="true">
        <?php for ($i = 0; $i < 36; $i++): ?>
            <span class="w-1.5 h-1.5 rounded-full bg-indigo-300"></span>
        <?php endfor; ?>
    </div>

    <div class="absolute bottom-12 left-16 hidden lg:grid grid-cols-6 gap-4 opacity-45" aria-hidden="true">
        <?php for ($i = 0; $i < 36; $i++): ?>
            <span class="w-1.5 h-1.5 rounded-full bg-indigo-300"></span>
        <?php endfor; ?>
    </div>

    <div class="w-full max-w-[480px] rounded-xl bg-white shadow-md p-8 sm:p-10 relative z-10">
        <div class="mb-8">
            <img class="h-16 w-auto mb-6" src="<?php echo BASE_URL; ?>/assets/iujo.png" alt="IUJO Logo">
            <h1 class="text-4xl leading-tight text-slate-700 font-medium mb-2">Bienvenido al socio economico! 👋</h1>
            <p class="text-slate-500 text-lg">Por favor, coloca tu usuario y contraseña</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>/login" method="POST" class="space-y-5" novalidate>
            <div>
                <label for="usuario" class="label-field">Usuario</label>
                <input
                    id="usuario"
                    name="usuario"
                    type="text"
                    class="input-field focus:ring-indigo-500"
                    placeholder="Usuario"
                    value="<?php echo htmlspecialchars($oldUser); ?>"
                    autocomplete="username"
                    required>
            </div>

            <div>
                <label for="contrasena" class="label-field">Contraseña</label>
                <div class="relative">
                    <input
                        id="contrasena"
                        name="contrasena"
                        type="password"
                        class="input-field pr-12 focus:ring-indigo-500"
                        placeholder="********"
                        autocomplete="current-password"
                        required>
                    <button
                        id="togglePassword"
                        type="button"
                        class="absolute inset-y-0 right-0 px-4 text-slate-500 hover:text-slate-700"
                        aria-label="Mostrar u ocultar contraseña">
                        <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 hidden">
                            <path d="m1 1 22 22"></path>
                            <path d="M10.58 10.58a2 2 0 0 0 2.83 2.83"></path>
                            <path d="M9.88 4.24A10.94 10.94 0 0 1 12 4c7 0 11 8 11 8a17.33 17.33 0 0 1-4.27 5.49"></path>
                            <path d="M6.61 6.61A17.39 17.39 0 0 0 1 12s4 8 11 8a10.94 10.94 0 0 0 5.35-1.42"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full rounded-md bg-indigo-500 hover:bg-indigo-600 text-white text-xl font-semibold py-2.5 transition duration-200 shadow-sm">
                Login
            </button>
        </form>
    </div>
</div>

<script>
    (function() {
        const passwordInput = document.getElementById('contrasena');
        const toggleButton = document.getElementById('togglePassword');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');

        if (!passwordInput || !toggleButton || !eyeOpen || !eyeClosed) {
            return;
        }

        toggleButton.addEventListener('click', function() {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            eyeOpen.classList.toggle('hidden', isPassword);
            eyeClosed.classList.toggle('hidden', !isPassword);
        });
    })();
</script>
