<?php
if (!isset($assetBase)) {
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
    $assetBase = $basePath . '/assets';
}

// Cache busting: avoid browser hard-cache when output.css changes.
// Determine which CSS file exists in the current entrypoint's assets folder.
$cssCandidates = ['output.css'];
$cssFile = 'output.css';
$cssVersion = null;

$scriptFilename = $_SERVER['SCRIPT_FILENAME'] ?? '';
$assetsDiskDir = $scriptFilename ? (dirname($scriptFilename) . DIRECTORY_SEPARATOR . 'assets') : '';
foreach ($cssCandidates as $candidate) {
    $candidateDiskPath = $assetsDiskDir
        ? ($assetsDiskDir . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . $candidate)
        : '';
    if ($candidateDiskPath && is_file($candidateDiskPath)) {
        $cssFile = $candidate;
        $cssVersion = @filemtime($candidateDiskPath) ?: null;
        break;
    }
}

$cssHref = $assetBase . '/css/' . $cssFile;
if ($cssVersion !== null) {
    $cssHref .= '?v=' . $cssVersion;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Formulario Socioeconómico'; ?></title>
    <link rel="icon" href="<?php echo BASE_URL; ?>/assets/FeyAlegria.svg" type="image/x-icon">
    <script>
        (function () {
            const storageKey = 'socioeconomico-theme';
            const root = document.documentElement;
            const savedTheme = localStorage.getItem(storageKey);
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = savedTheme || (prefersDark ? 'dark' : 'light');
            root.classList.toggle('dark', theme === 'dark');
            root.dataset.theme = theme;
        }());
    </script>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-100 min-h-screen py-8 transition-colors duration-300">
    <?php echo $content; ?>
</body>

</html>