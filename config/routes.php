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
$router->get('/admin/estadisticas', 'AdminController@estadisticas');
$router->get('/admin/usuarios', 'AdminController@users');
$router->get('/admin/respuestas', 'AdminController@responses');
$router->get('/admin/respuestas/:id', 'AdminController@responseDetail');
$router->get('/admin/catalogos', 'AdminController@catalogs');

// Acciones Admin Usuarios
$router->post('/admin/usuarios/create', 'AdminUsersController@create');
$router->post('/admin/usuarios/update/:id', 'AdminUsersController@update');
$router->post('/admin/usuarios/delete/:id', 'AdminUsersController@delete');

// Ruta 404
$router->notFound(function () {
    $controller = new App\Controllers\ErrorsController();
    $controller->show404();
});

return $router;
