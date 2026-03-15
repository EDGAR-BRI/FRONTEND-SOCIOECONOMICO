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

        // Filtros/paginación vía querystring
        $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
        $carreraId = isset($_GET['carrera_id']) ? trim((string)$_GET['carrera_id']) : '';
        $estrato = isset($_GET['estrato']) ? trim((string)$_GET['estrato']) : '';
        // Compatibilidad: UI anterior enviaba "estado".
        if ($estrato === '' && isset($_GET['estado'])) {
            $estrato = trim((string)$_GET['estado']);
        }

        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) {
            $page = 1;
        }

        $perPage = isset($_GET['per_page']) && is_numeric($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        if ($perPage < 1) {
            $perPage = 10;
        }
        if ($perPage > 100) {
            $perPage = 100;
        }

        $params = [
            'page' => $page,
            'per_page' => $perPage,
        ];
        if ($q !== '') {
            $params['q'] = $q;
        }
        if ($carreraId !== '' && is_numeric($carreraId) && (int)$carreraId > 0) {
            $params['carrera_id'] = (int)$carreraId;
        }
        if ($estrato !== '') {
            $params['estrato'] = $estrato;
        }

        // Cargar listado de encuestas (resumen)
        $encuestas = [
            'items' => [],
            'pagination' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => 0,
                'total_pages' => 1,
            ],
        ];

        $apiError = null;

        try {
            // Forzar Authorization explícito (evita casos donde el header no llegue por sesión)
            if (session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION['auth_token'])) {
                $this->apiService->setHeader('Authorization', 'Bearer ' . (string) $_SESSION['auth_token']);
            }

            $response = $this->apiService->get('/encuesta', $params);
            $payload = isset($response['data']) && is_array($response['data']) ? $response['data'] : null;

            if ($response['success'] && $payload) {
                // Formato estándar: {success, data, message}
                $data = (isset($payload['success']) && array_key_exists('data', $payload) && is_array($payload['data']))
                    ? $payload['data']
                    : $payload;

                if (isset($data['items']) && is_array($data['items'])) {
                    $encuestas['items'] = $data['items'];
                }
                if (isset($data['pagination']) && is_array($data['pagination'])) {
                    $encuestas['pagination'] = array_merge($encuestas['pagination'], $data['pagination']);
                }
            } else {
                $message = 'No se pudieron cargar las respuestas.';
                if (is_array($payload) && isset($payload['message']) && is_string($payload['message']) && trim($payload['message']) !== '') {
                    $message = $payload['message'];
                }

                $apiError = [
                    'status' => isset($response['status']) ? (int)$response['status'] : 0,
                    'message' => $message,
                ];
            }
        } catch (\Exception $e) {
            $apiError = [
                'status' => 0,
                'message' => 'Error de conexión con el servidor: ' . $e->getMessage(),
            ];
        }

        // Catálogo de carreras para el filtro
        $carreras = [];
        try {
            $institutoId = null;
            if (session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION['auth_user'])) {
                $user = $_SESSION['auth_user'];
                if (isset($user['instituto']) && is_array($user['instituto']) && !empty($user['instituto']['id'])) {
                    $institutoId = (int)$user['instituto']['id'];
                }
            }

            $catalogParams = [];
            if (!empty($institutoId)) {
                $catalogParams['instituto_id'] = $institutoId;
            }

            $catalogResponse = $this->apiService->get('/catalogo/carrera', $catalogParams);
            $catalogPayload = isset($catalogResponse['data']) && is_array($catalogResponse['data']) ? $catalogResponse['data'] : null;

            if ($catalogResponse['success'] && $catalogPayload) {
                $catalogData = (isset($catalogPayload['success']) && array_key_exists('data', $catalogPayload) && is_array($catalogPayload['data']))
                    ? $catalogPayload['data']
                    : $catalogPayload;

                if (is_array($catalogData)) {
                    $carreras = $catalogData;
                }
            }
        } catch (\Exception $e) {
            $carreras = [];
        }
        

        $this->view('admin/responses', [
            'title' => 'Respuestas a Encuestas | Admin',
            'current_page' => 'responses',
            'encuestas' => $encuestas,
            'carreras' => $carreras,
            'apiError' => $apiError,
            'filters' => [
                'q' => $q,
                'carrera_id' => $carreraId,
                'estrato' => $estrato,
                'page' => $page,
                'per_page' => $perPage,
            ],
        ], 'admin');
    }

    public function responseDetail($id)
    {
        $this->checkAuth();

        $id = is_numeric($id) ? (int)$id : 0;

        $encuesta = null;
        $apiError = null;

        if ($id <= 0) {
            $apiError = ['status' => 400, 'message' => 'ID inválido'];
        } else {
            try {
                // Forzar Authorization explícito (evita casos donde el header no llegue por sesión)
                if (session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION['auth_token'])) {
                    $this->apiService->setHeader('Authorization', 'Bearer ' . (string) $_SESSION['auth_token']);
                }

                $response = $this->apiService->get('/encuesta/' . $id);
                $payload = isset($response['data']) && is_array($response['data']) ? $response['data'] : null;

                if ($response['success'] && $payload) {
                    $data = (isset($payload['success']) && array_key_exists('data', $payload) && is_array($payload['data']))
                        ? $payload['data']
                        : $payload;

                    if (is_array($data)) {
                        $encuesta = $data;
                    }
                } else {
                    $message = 'No se pudo cargar el detalle de la encuesta.';
                    if (is_array($payload) && isset($payload['message']) && is_string($payload['message']) && trim($payload['message']) !== '') {
                        $message = $payload['message'];
                    }

                    $apiError = [
                        'status' => isset($response['status']) ? (int)$response['status'] : 0,
                        'message' => $message,
                    ];
                }
            } catch (\Exception $e) {
                $apiError = [
                    'status' => 0,
                    'message' => 'Error de conexión con el servidor: ' . $e->getMessage(),
                ];
            }
        }

        $this->view('admin/response_detail', [
            'title' => 'Detalle de Encuesta | Admin',
            'current_page' => 'responses',
            'encuesta' => $encuesta,
            'apiError' => $apiError,
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
