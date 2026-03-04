<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Formulario Socioeconómico'; ?></title>
    <link rel="icon" href="<?php echo BASE_URL; ?>/assets/FeyAlegria.svg" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/output.css">
</head>

<body class="bg-gray-100 min-h-screen py-8">
    <?php echo $content; ?>
</body>

</html>