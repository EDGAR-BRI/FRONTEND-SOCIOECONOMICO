<?php

/**
 * Definición de rutas de la aplicación
 */

use Core\Router;

$router = new Router();

// Rutas GET
$router->get('/', 'HomeController@index');
$router->get('/:sede/formulario', 'FormController@index');
$router->get('/login', 'AuthController@login');
$router->get('/success', 'FormController@success');

// Rutas POST
$router->post('/login', 'AuthController@authenticate');
$router->post('/logout', 'AuthController@logout');
$router->post('/:sede/formulario/submit', 'FormController@submit');

// Rutas Admin
$router->get('/admin', 'AdminController@index');
$router->get('/admin/usuarios', 'AdminController@users');
$router->get('/admin/respuestas', 'AdminController@responses');
$router->get('/admin/respuestas/:id', 'AdminController@responseDetail');
$router->get('/admin/catalogos', 'AdminController@catalogs');

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
