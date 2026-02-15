<?php

/**
 * Definición de rutas de la aplicación
 */

use Core\Router;

$router = new Router();

// Rutas GET
$router->get('/', 'FormController@index');
$router->get('/success', 'FormController@success');

// Rutas POST
$router->post('/submit', 'FormController@submit');

// Ruta 404
$router->notFound(function () {
    $controller = new class extends Core\Controller {
        public function show404()
        {
            $this->view('errors/404', [], 'main');
        }
    };
    $controller->show404();
});

return $router;
