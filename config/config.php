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

// Base URL para redirecciones y assets (ej: /FRONTEND-SOCIOECONOMICO)
// Si el proyecto se mueve a la raíz del dominio, esto sería simplemente ''
define('BASE_URL', '/FRONTEND-SOCIOECONOMICO');

// Configuración de la aplicación
define('APP_NAME', 'Formulario Socioeconómico');
define('APP_URL', 'http://localhost');

// Configuración de API
define('API_BASE_URL', 'http://localhost/BACKEND-SOCIOECONOMICO/public');
define('API_TOKEN', ''); // Agregar token cuando sea necesario
define('API_TIMEOUT', 30); // Timeout en segundos

// Multi-tenant (opcional): define el Instituto (tenant) para todas las llamadas al backend.
// Puedes setearlo por variable de entorno INSTITUTO_ID (recomendado) o dejarlo vacío para usar el fallback del backend.
$envInstitutoId = getenv('INSTITUTO_ID');
define('INSTITUTO_ID', $envInstitutoId !== false && is_numeric($envInstitutoId) ? (int)$envInstitutoId : null);

// Multi-sede (opcional): si usas URLs tipo /:sede/formulario, puedes mapear ese slug al instituto_id del backend.
// Esto permite que estudiantes SIN usuario queden asociados a su instituto solo por la URL.
// Ejemplo: /bqto/formulario => instituto_id=1
define('SEDE_INSTITUTO_MAP', [
    // 'bqto' => 1,
    // 'carora' => 2,
]);

// Otras constantes
define('SITE_URL', 'http://localhost');
define('SITE_NAME', 'Formulario Socioeconómico');
