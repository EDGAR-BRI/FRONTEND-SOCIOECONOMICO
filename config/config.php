<?php

/**
 * Archivo de configuración general
 */

// Configuración de errores (desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de zona horaria
date_default_timezone_set('America/Caracas');

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Rutas del proyecto
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('CONFIG_PATH', ROOT_PATH . '/config');

// Configuración de la aplicación
define('APP_NAME', 'Formulario Socioeconómico');
define('APP_URL', 'http://localhost');

// Configuración de API
define('API_BASE_URL', 'http://localhost/BACKEND-SOCIOECONOMICO/public');
define('API_TOKEN', ''); // Agregar token cuando sea necesario
define('API_TIMEOUT', 30); // Timeout en segundos

// Otras constantes
define('SITE_URL', 'http://localhost');
define('SITE_NAME', 'Formulario Socioeconómico');
