<?php

namespace Core;

/**
 * Router - Sistema de enrutamiento simple
 */
class Router
{
    private $routes = [];
    private $notFoundCallback;

    /**
     * Registra una ruta GET
     * 
     * @param string $path Ruta URL
     * @param string $controller Controlador en formato "Controller@method"
     */
    public function get($path, $controller)
    {
        $this->addRoute('GET', $path, $controller);
    }

    /**
     * Registra una ruta POST
     * 
     * @param string $path Ruta URL
     * @param string $controller Controlador en formato "Controller@method"
     */
    public function post($path, $controller)
    {
        $this->addRoute('POST', $path, $controller);
    }

    /**
     * Agrega una ruta al sistema
     * 
     * @param string $method Método HTTP
     * @param string $path Ruta URL
     * @param string $controller Controlador
     */
    private function addRoute($method, $path, $controller)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller
        ];
    }

    /**
     * Define el callback para rutas no encontradas (404)
     * 
     * @param callable $callback Función a ejecutar
     */
    public function notFound($callback)
    {
        $this->notFoundCallback = $callback;
    }

    /**
     * Ejecuta el router y procesa la petición actual
     */
    public function run()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remover el directorio base si existe
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptName !== '/') {
            $requestUri = str_replace($scriptName, '', $requestUri);
        }

        // Asegurar que empiece con /
        $requestUri = '/' . ltrim($requestUri, '/');

        // Buscar la ruta correspondiente
        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $this->matchPath($route['path'], $requestUri)) {
                $this->dispatch($route['controller']);
                return;
            }
        }

        // Si no se encontró la ruta, ejecutar callback 404
        if ($this->notFoundCallback) {
            call_user_func($this->notFoundCallback);
        } else {
            http_response_code(404);
            echo "404 - Página no encontrada";
        }
    }

    /**
     * Verifica si la ruta coincide con el patrón
     * 
     * @param string $pattern Patrón de la ruta
     * @param string $path Ruta actual
     * @return bool
     */
    private function matchPath($pattern, $path)
    {
        return $pattern === $path;
    }

    /**
     * Despacha la petición al controlador correspondiente
     * 
     * @param string $controller Controlador en formato "Controller@method"
     */
    private function dispatch($controller)
    {
        list($controllerName, $method) = explode('@', $controller);

        // Construir el nombre completo de la clase
        $controllerClass = "App\\Controllers\\{$controllerName}";

        if (!class_exists($controllerClass)) {
            throw new \Exception("Controlador {$controllerClass} no encontrado");
        }

        $controllerInstance = new $controllerClass();

        if (!method_exists($controllerInstance, $method)) {
            throw new \Exception("Método {$method} no encontrado en {$controllerClass}");
        }

        // Ejecutar el método del controlador
        $controllerInstance->$method();
    }
}
