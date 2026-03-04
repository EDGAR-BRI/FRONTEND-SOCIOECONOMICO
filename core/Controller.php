<?php

namespace Core;

/**
 * Controller - Clase base para todos los controladores
 */
class Controller
{
    /**
     * Carga una vista
     * 
     * @param string $view Nombre de la vista (ej: 'form/index')
     * @param array $data Datos a pasar a la vista
     * @param string $layout Layout a usar (por defecto 'main')
     */
    protected function view($view, $data = [], $layout = 'main')
    {
        // Base path / assets path (works with both /index.php and /public/index.php)
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
        if (!isset($data['assetBase'])) {
            $data['assetBase'] = $basePath . '/assets';
        }

        // Extraer datos para que estén disponibles en la vista
        extract($data);

        // Iniciar buffer de salida
        ob_start();

        // Cargar la vista
        $viewPath = dirname(__DIR__) . "/app/views/{$view}.php";

        if (!file_exists($viewPath)) {
            throw new \Exception("Vista {$view} no encontrada");
        }

        require $viewPath;

        // Obtener el contenido de la vista
        $content = ob_get_clean();

        // Si hay layout, cargarlo
        if ($layout) {
            $layoutPath = dirname(__DIR__) . "/app/views/layouts/{$layout}.php";

            if (!file_exists($layoutPath)) {
                throw new \Exception("Layout {$layout} no encontrado");
            }

            require $layoutPath;
        } else {
            echo $content;
        }
    }

    /**
     * Retorna una respuesta JSON
     * 
     * @param mixed $data Datos a retornar
     * @param int $statusCode Código de estado HTTP
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Redirige a otra URL
     * 
     * @param string $url URL de destino
     */
    protected function redirect($url)
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * Obtiene un parámetro POST
     * 
     * @param string $key Clave del parámetro
     * @param mixed $default Valor por defecto
     * @return mixed
     */
    protected function post($key, $default = null)
    {
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }

    /**
     * Obtiene un parámetro GET
     * 
     * @param string $key Clave del parámetro
     * @param mixed $default Valor por defecto
     * @return mixed
     */
    protected function get($key, $default = null)
    {
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }

    /**
     * Verifica si la petición es POST
     * 
     * @return bool
     */
    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Verifica si la petición es GET
     * 
     * @return bool
     */
    protected function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
}
