<?php

/**
 * Definición de rutas de la aplicación
 */

use Core\Router;

$router = new Router();

// Rutas GET
$router->get('/', 'FormController@index');
$router->get('/login', 'FormController@login');
$router->get('/success', 'FormController@success');

// Rutas POST
$router->post('/login', 'FormController@authenticate');
$router->post('/logout', 'FormController@logout');
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
