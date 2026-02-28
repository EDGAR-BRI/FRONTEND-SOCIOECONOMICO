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
    <link rel="icon" href="<?php echo $assetBase; ?>/FeyAlegria.svg" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($cssHref, ENT_QUOTES, 'UTF-8'); ?>">
</head>


<body class="bg-gray-100 min-h-screen py-8">
    <?php echo $content; ?>
</body>

</html>