<?php

namespace App\Controllers;

use Core\Controller;
use App\Services\ApiService;

/**
 * AdminController - Controlador para el Dashboard Administrativo
 */
class AdminController extends Controller
{
    private $apiService;

    public function __construct()
    {
        
        $this->apiService = new ApiService();
    }

    /**
     * Verifica la autenticación antes de ejecutar un método
     */
    private function checkAuth()
    {
        if (!$this->isAuthenticated()) {
            $this->redirect(BASE_URL . '/login');
            exit; 
        }
    }

    /**
     * Verifica si existe una sesión activa
     */
    private function isAuthenticated()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return !empty($_SESSION['auth_user']) && !empty($_SESSION['auth_token']);
    }

    /**
     * Vista principal del Dashboard
     */
    public function index()
    {
        $this->checkAuth();
        
        // Renderizar vista usando el layout 'admin'
        $this->view('admin/dashboard', [
            'title' => 'Dashboard | Admin',
            'current_page' => 'dashboard'
        ], 'admin');
    }

    /**
     * Vista de gestión de usuarios
     */
    public function users()
    {
        $this->checkAuth();
        
        $this->view('admin/users', [
            'title' => 'Gestión de Usuarios | Admin',
            'current_page' => 'users'
        ], 'admin');
    }

    /**
     * Vista de gestión de respuestas
     */
    public function responses()
    {
        $this->checkAuth();
        
        $this->view('admin/responses', [
            'title' => 'Respuestas a Encuestas | Admin',
            'current_page' => 'responses'
        ], 'admin');
    }

    /**
     * Vista de gestión de catálogos
     */
    public function catalogs()
    {
        $this->checkAuth();
        
        $this->view('admin/catalogs', [
            'title' => 'Gestión de Catálogos | Admin',
            'current_page' => 'catalogs'
        ], 'admin');
    }
}
