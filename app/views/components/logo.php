<?php
$logoColorMode = isset($logoColorMode) && is_string($logoColorMode) ? $logoColorMode : 'theme';
$logoTextClass = $logoColorMode === 'white' ? 'text-white' : 'text-black dark:text-white';
$logoSede = isset($sede) && is_string($sede) ? strtoupper(trim($sede)) : '';
$isIUSF = $logoSede === 'IUSF';
?>
<div id="Logo" class="group flex items-center justify-center gap-5 mt-auto mb-6 w-auto h-20 <?php echo $logoTextClass; ?>">
    <div class="flex items-center justify-center leading-none [&>svg]:w-auto [&>svg]:h-14 [&>svg]:block max-[600px]:[&>svg]:h-10">
        <?php include ROOT_PATH . '/public/assets/svg/' . ($isIUSF ? 'IUSF.svg' : 'IUJO.svg'); ?>
    </div>
        <div class="flex items-center justify-center leading-none [&>svg]:w-auto [&>svg]:h-20 [&>svg]:block max-[600px]:[&>svg]:h-10 group-hover:animate-heartbeat origin-center will-change-transform">
        <?php include ROOT_PATH . '/public/assets/svg/FyA-logo.svg'; ?>
    </div>
    <div class="flex items-center justify-center leading-none [&>svg]:w-auto [&>svg]:h-24 [&>svg]:block max-[600px]:[&>svg]:h-10">
        <?php include ROOT_PATH . '/public/assets/svg/' . ($isIUSF ? 'IUSF-letra.svg' : 'IUJO-letras.svg'); ?>
    </div>
</div>