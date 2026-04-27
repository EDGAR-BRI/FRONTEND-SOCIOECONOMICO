<?php
$logoColorMode = isset($logoColorMode) && is_string($logoColorMode) ? $logoColorMode : 'theme';
$logoTextClass = $logoColorMode === 'white' ? 'text-white' : 'text-black dark:text-white';
?>
<div id="Logo" class=" group flex items-center justify-center gap-5 mt-auto w-auto h-24 <?php echo $logoTextClass; ?>">
    <div class="flex items-center justify-center leading-none [&>svg]:w-auto [&>svg]:h-12 [&>svg]:block max-[600px]:[&>svg]:h-10">
        <?php include ROOT_PATH . '/public/assets/svg/IUJO.svg'; ?>
    </div>
        <div class="flex items-center justify-center leading-none [&>svg]:w-auto [&>svg]:h-16 [&>svg]:block max-[600px]:[&>svg]:h-10 group-hover:animate-heartbeat origin-center will-change-transform">
        <?php include ROOT_PATH . '/public/assets/svg/FyA-logo.svg'; ?>
    </div>
</div>