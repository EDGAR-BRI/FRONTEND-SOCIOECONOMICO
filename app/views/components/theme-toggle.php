<?php
$themeComponentMode = isset($themeComponentMode) && is_string($themeComponentMode)
    ? $themeComponentMode
    : 'toggle';

if ($themeComponentMode === 'bootstrap'):
?>
<script>
(function () {
    const storageKey = 'socioeconomico-theme';
    const root = document.documentElement;
    const savedTheme = localStorage.getItem(storageKey);
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const hasValidSavedTheme = savedTheme === 'dark' || savedTheme === 'light';
    const theme = hasValidSavedTheme ? savedTheme : (prefersDark ? 'dark' : 'light');
    root.classList.toggle('dark', theme === 'dark');
    root.dataset.theme = theme;
}());
</script>
<?php
return;
endif;

$toggleId = isset($themeToggleId) && is_string($themeToggleId) && $themeToggleId !== ''
    ? $themeToggleId
    : 'themeToggle';

$toggleAriaLabel = isset($themeToggleAriaLabel) && is_string($themeToggleAriaLabel) && $themeToggleAriaLabel !== ''
    ? $themeToggleAriaLabel
    : 'Cambiar entre modo claro y oscuro';
?>
<label class="inline-flex items-center relative cursor-pointer shrink-0" for="<?php echo htmlspecialchars($toggleId); ?>" aria-label="<?php echo htmlspecialchars($toggleAriaLabel); ?>">
    <input class="peer hidden" id="<?php echo htmlspecialchars($toggleId); ?>" data-theme-toggle type="checkbox" />
    <div class="relative w-[52px] h-[28px] bg-slate-200 peer-checked:bg-zinc-500 rounded-full after:absolute after:content-[''] after:w-[22px] after:h-[22px] after:bg-gradient-to-r from-orange-500 to-yellow-400 peer-checked:after:from-zinc-900 peer-checked:after:to-zinc-900 after:rounded-full after:top-[3px] after:left-[3px] active:after:w-[28px] peer-checked:after:left-[49px] peer-checked:after:translate-x-[-100%] shadow-sm duration-300 after:duration-300 after:shadow-md"></div>
    <svg height="0" width="100" viewBox="0 0 24 24" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" class="fill-white peer-checked:opacity-60 absolute w-3.5 h-3.5 left-[6px]">
        <path d="M12,17c-2.76,0-5-2.24-5-5s2.24-5,5-5,5,2.24,5,5-2.24,5-5,5ZM13,0h-2V5h2V0Zm0,19h-2v5h2v-5ZM5,11H0v2H5v-2Zm19,0h-5v2h5v-2Zm-2.81-6.78l-1.41-1.41-3.54,3.54,1.41,1.41,3.54-3.54ZM7.76,17.66l-1.41-1.41-3.54,3.54,1.41,1.41,3.54-3.54Zm0-11.31l-3.54-3.54-1.41,1.41,3.54,3.54,1.41-1.41Zm13.44,13.44l-3.54-3.54-1.41,1.41,3.54,3.54,1.41-1.41Z"></path>
    </svg>
    <svg height="512" width="512" viewBox="0 0 24 24" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" class="fill-black opacity-60 peer-checked:opacity-70 peer-checked:fill-white absolute w-3.5 h-3.5 right-[6px]">
        <path d="M12.009,24A12.067,12.067,0,0,1,.075,10.725,12.121,12.121,0,0,1,10.1.152a13,13,0,0,1,5.03.206,2.5,2.5,0,0,1,1.8,1.8,2.47,2.47,0,0,1-.7,2.425c-4.559,4.168-4.165,10.645.807,14.412h0a2.5,2.5,0,0,1-.7,4.319A13.875,13.875,0,0,1,12.009,24Zm.074-22a10.776,10.776,0,0,0-1.675.127,10.1,10.1,0,0,0-8.344,8.8A9.928,9.928,0,0,0,4.581,18.7a10.473,10.473,0,0,0,11.093,2.734.5.5,0,0,0,.138-.856h0C9.883,16.1,9.417,8.087,14.865,3.124a.459.459,0,0,0,.127-.465.491.491,0,0,0-.356-.362A10.68,10.68,0,0,0,12.083,2ZM20.5,12a1,1,0,0,1-.97-.757l-.358-1.43L17.74,9.428a1,1,0,0,1,.035-1.94l1.4-.325.351-1.406a1,1,0,0,1,1.94,0l.355,1.418,1.418.355a1,1,0,0,1,0,1.94l-1.418.355-.355,1.418A1,1,0,0,1,20.5,12ZM16,14a1,1,0,0,0,2,0A1,1,0,0,0,16,14Zm6,4a1,1,0,0,0,2,0A1,1,0,0,0,22,18Z"></path>
    </svg>
</label>

<?php if (empty($GLOBALS['__theme_toggle_logic_rendered'])): ?>
<?php $GLOBALS['__theme_toggle_logic_rendered'] = true; ?>
<script>
(function () {
    const storageKey = 'socioeconomico-theme';
    const root = document.documentElement;
    const toggles = document.querySelectorAll('[data-theme-toggle]');
    const systemThemeQuery = window.matchMedia ? window.matchMedia('(prefers-color-scheme: dark)') : null;

    const hasValidSavedTheme = () => {
        const savedTheme = localStorage.getItem(storageKey);
        return savedTheme === 'dark' || savedTheme === 'light';
    };

    const getInitialTheme = () => {
        const savedTheme = localStorage.getItem(storageKey);
        if (savedTheme === 'dark' || savedTheme === 'light') {
            return savedTheme;
        }
        return systemThemeQuery && systemThemeQuery.matches ? 'dark' : 'light';
    };

    const applyTheme = (theme, persist) => {
        const isDark = theme === 'dark';
        root.classList.toggle('dark', isDark);
        root.dataset.theme = theme;

        toggles.forEach((toggle) => {
            toggle.checked = isDark;
        });

        if (persist) {
            localStorage.setItem(storageKey, theme);
        }
    };

    applyTheme(getInitialTheme(), false);

    toggles.forEach((toggle) => {
        toggle.addEventListener('change', function () {
            applyTheme(this.checked ? 'dark' : 'light', true);
        });
    });

    if (!hasValidSavedTheme() && systemThemeQuery) {
        const syncWithSystemTheme = (event) => {
            applyTheme(event.matches ? 'dark' : 'light', false);
        };

        if (typeof systemThemeQuery.addEventListener === 'function') {
            systemThemeQuery.addEventListener('change', syncWithSystemTheme);
        } else if (typeof systemThemeQuery.addListener === 'function') {
            systemThemeQuery.addListener(syncWithSystemTheme);
        }
    }
}());
</script>
<?php endif; ?>