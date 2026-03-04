<?php

namespace App\Controllers;

use Core\Controller;

/**
 * AuthController - Controlador para la autenticación
 */
class AuthController extends Controller
{
    /**
     * Muestra la vista de login
     */
    public function login()
    {
        if ($this->isAuthenticated()) {
            $this->redirect(BASE_URL . '/admin');
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $error = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : null;
        $oldUser = isset($_SESSION['login_user']) ? $_SESSION['login_user'] : '';

        unset($_SESSION['login_error']);
        unset($_SESSION['login_user']);

        $this->view('auth/login', [
            'error' => $error,
            'oldUser' => $oldUser,
            'title' => 'Iniciar sesión'
        ]);
    }

    /**
     * Procesa la autenticación del usuario
     */
    public function authenticate()
    {
        if (!$this->isPost()) {
            $this->redirect(BASE_URL . '/login');
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $usuario = trim((string) $this->post('usuario', ''));
        $contrasena = trim((string) $this->post('contrasena', ''));

        if ($usuario === '' || $contrasena === '') {
            $_SESSION['login_error'] = 'Debes ingresar usuario y contraseña.';
            $_SESSION['login_user'] = $usuario;
            $this->redirect(BASE_URL . '/login');
            return;
        }

        // Aquí más adelante validarás contra la base de datos a través de la API
        $_SESSION['auth_user'] = $usuario;
        $this->redirect(BASE_URL . '/admin');
    }

    /**
     * Cierra la sesión del usuario
     */
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        unset($_SESSION['auth_user']);
        $this->redirect(BASE_URL . '/login');
    }

    /**
     * Verifica si el usuario está autenticado
     */
    private function isAuthenticated()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return !empty($_SESSION['auth_user']);
    }
}
