<?php

namespace App\Controllers;

use Core\Controller;
use App\Services\ApiService;
use App\Services\CatalogoService;
use App\Services\UsuarioService;
use App\Services\EncuestaService;

/**
 * AdminController - Controlador para el Dashboard Administrativo
 */
class AdminController extends Controller
{
    private $apiService;
    private $catalogoService;
    private $usuarioService;
    private $encuestaService;

    public function __construct()
    {
        
        $this->apiService = new ApiService();
        $this->catalogoService = new CatalogoService($this->apiService);
        $this->usuarioService = new UsuarioService($this->apiService);
        $this->encuestaService = new EncuestaService($this->apiService);
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

    private function actorRolCodigo()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $authUser = isset($_SESSION['auth_user']) && is_array($_SESSION['auth_user']) ? $_SESSION['auth_user'] : [];
        if (isset($authUser['rol']) && is_array($authUser['rol']) && !empty($authUser['rol']['codigo'])) {
            return (string)$authUser['rol']['codigo'];
        }
        return null;
    }

    private function requireSuperAdmin()
    {
        $rol = $this->actorRolCodigo();
        if ($rol !== 'SUPER_ADMIN') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['flash_type'] = 'error';
            $_SESSION['flash_message'] = 'No autorizado: solo SUPER_ADMIN puede acceder a esta sección.';
            $this->redirect(BASE_URL . '/admin');
        }
    }

    /**
     * Vista principal del Dashboard
     */
    public function index()
    {
        $this->checkAuth();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $dashboard = [
            'total_encuestas' => null,
            'total_usuarios' => null,
            'ultima_encuesta' => null,
        ];

        $encuestasRecientes = [];
        $apiError = null;

        try {
            $encuestasRecientes = $this->encuestaService->ultimas(5);

            // Total de encuestas (sin inferir estados como "pendiente")
            $dashboard['total_encuestas'] = $this->encuestaService->totalPorFiltro(null);

            // Fecha de la última encuesta (si el endpoint devuelve orden descendente)
            if (!empty($encuestasRecientes) && is_array($encuestasRecientes[0]) && !empty($encuestasRecientes[0]['creado'])) {
                $dashboard['ultima_encuesta'] = (string)$encuestasRecientes[0]['creado'];
            }

            // Total de usuarios
            $usuariosResponse = $this->usuarioService->listar();
            $usuariosPayload = isset($usuariosResponse['data']) && is_array($usuariosResponse['data']) ? $usuariosResponse['data'] : null;
            if (!empty($usuariosResponse['success']) && $usuariosPayload) {
                $usuariosData = (isset($usuariosPayload['success']) && array_key_exists('data', $usuariosPayload) && is_array($usuariosPayload['data']))
                    ? $usuariosPayload['data']
                    : $usuariosPayload;
                $usuariosItems = isset($usuariosData['items']) && is_array($usuariosData['items']) ? $usuariosData['items'] : [];
                $dashboard['total_usuarios'] = count($usuariosItems);
            }
        } catch (\Exception $e) {
            $apiError = [
                'status' => 0,
                'message' => 'Error de conexión con el servidor: ' . $e->getMessage(),
            ];
        }
        
        // Renderizar vista usando el layout 'admin'
        $this->view('admin/dashboard', [
            'title' => 'Dashboard | Admin',
            'current_page' => 'dashboard',
            'dashboard' => $dashboard,
            'encuestasRecientes' => $encuestasRecientes,
            'apiError' => $apiError,
        ], 'admin');
    }

    /**
     * Vista de estadísticas (maquetación con datos mock)
     */
    public function estadisticas()
    {
        $this->checkAuth();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $from = isset($_GET['from']) ? trim((string)$_GET['from']) : '';
        $to = isset($_GET['to']) ? trim((string)$_GET['to']) : '';

        $parseDate = function ($value) {
            if (!is_string($value) || trim($value) === '') {
                return null;
            }
            $dt = \DateTime::createFromFormat('Y-m-d', $value);
            if (!$dt) {
                return null;
            }
            $errors = \DateTime::getLastErrors();
            if (!empty($errors['warning_count']) || !empty($errors['error_count'])) {
                return null;
            }
            $dt->setTime(0, 0, 0);
            return $dt;
        };

        $toDt = $parseDate($to);
        if ($toDt === null) {
            $toDt = new \DateTime('today');
        }

        $fromDt = $parseDate($from);
        if ($fromDt === null) {
            $fromDt = (clone $toDt)->modify('-29 days');
        }

        if ($fromDt > $toDt) {
            $tmp = $fromDt;
            $fromDt = $toDt;
            $toDt = $tmp;
        }

        // Evitar rangos excesivos en mock
        $maxDays = 366;
        $diffDays = (int)$fromDt->diff($toDt)->format('%a') + 1;
        if ($diffDays > $maxDays) {
            $fromDt = (clone $toDt)->modify('-' . ($maxDays - 1) . ' days');
            $diffDays = $maxDays;
        }

        // Serie temporal mock: encuestas por día
        $labels = [];
        $series = [];
        $cursor = clone $fromDt;
        while ($cursor <= $toDt) {
            $label = $cursor->format('Y-m-d');
            $labels[] = $label;
            $seed = abs((int)crc32('encuestas:' . $label));
            $value = 8 + ($seed % 17); // 8..24
            // Simular fines de semana más bajos
            $dow = (int)$cursor->format('N');
            if ($dow >= 6) {
                $value = (int)max(1, floor($value * 0.65));
            }
            $series[] = $value;
            $cursor->modify('+1 day');
        }

        $totalEncuestas = array_sum($series);
        $dias = count($series);
        $promedioDiario = $dias > 0 ? ($totalEncuestas / $dias) : 0;
        $maxDia = !empty($series) ? max($series) : 0;

        // Distribución mock de estratos (1..5)
        $estratoWeights = [
            1 => 0.14,
            2 => 0.24,
            3 => 0.30,
            4 => 0.20,
            5 => 0.12,
        ];
        $estratos = [];
        $assigned = 0;
        foreach ($estratoWeights as $estrato => $w) {
            $count = (int)floor($totalEncuestas * $w);
            $estratos[(string)$estrato] = $count;
            $assigned += $count;
        }
        // Ajustar remainder para cuadrar con el total
        $remainder = $totalEncuestas - $assigned;
        $estratoKeys = array_keys($estratos);
        $i = 0;
        while ($remainder > 0 && !empty($estratoKeys)) {
            $k = $estratoKeys[$i % count($estratoKeys)];
            $estratos[$k] += 1;
            $remainder--;
            $i++;
        }

        // Distribución mock por carreras
        $carrerasBase = [
            'Informática',
            'Administración',
            'Contaduría',
            'Educación',
            'Comunicación Social',
            'Enfermería',
            'Psicología',
        ];
        $carreraWeights = [0.18, 0.16, 0.13, 0.12, 0.11, 0.10, 0.20];
        $carreras = [];
        $assigned = 0;
        foreach ($carrerasBase as $idx => $name) {
            $w = isset($carreraWeights[$idx]) ? (float)$carreraWeights[$idx] : 0.1;
            $count = (int)floor($totalEncuestas * $w);
            $carreras[$name] = $count;
            $assigned += $count;
        }
        $remainder = $totalEncuestas - $assigned;
        $carreraKeys = array_keys($carreras);
        $i = 0;
        while ($remainder > 0 && !empty($carreraKeys)) {
            $k = $carreraKeys[$i % count($carreraKeys)];
            $carreras[$k] += 1;
            $remainder--;
            $i++;
        }

        $this->view('admin/estadisticas', [
            'title' => 'Estadísticas | Admin',
            'current_page' => 'stats',
            'filters' => [
                'from' => $fromDt->format('Y-m-d'),
                'to' => $toDt->format('Y-m-d'),
            ],
            'kpis' => [
                'total_encuestas' => $totalEncuestas,
                'promedio_diario' => $promedioDiario,
                'max_dia' => $maxDia,
                'dias' => $dias,
            ],
            'charts' => [
                'timeline' => [
                    'labels' => $labels,
                    'values' => $series,
                ],
                'estratos' => $estratos,
                'carreras' => $carreras,
            ],
        ], 'admin');
    }

    /**
     * Vista de gestión de usuarios
     */
    public function users()
    {
        $this->checkAuth();
        $this->requireSuperAdmin();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
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

        $flash = null;
        if (!empty($_SESSION['flash_message'])) {
            $flash = [
                'type' => isset($_SESSION['flash_type']) ? (string)$_SESSION['flash_type'] : 'info',
                'message' => (string)$_SESSION['flash_message'],
                'errors' => isset($_SESSION['flash_errors']) && is_array($_SESSION['flash_errors']) ? $_SESSION['flash_errors'] : [],
            ];
        }
        unset($_SESSION['flash_type'], $_SESSION['flash_message'], $_SESSION['flash_errors']);

        $authUser = isset($_SESSION['auth_user']) && is_array($_SESSION['auth_user']) ? $_SESSION['auth_user'] : [];
        $actorRol = null;
        if (isset($authUser['rol']) && is_array($authUser['rol']) && !empty($authUser['rol']['codigo'])) {
            $actorRol = (string)$authUser['rol']['codigo'];
        }

        $usuarios = [
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
            $response = $this->usuarioService->listar();
            $payload = isset($response['data']) && is_array($response['data']) ? $response['data'] : null;

            if (!empty($response['success']) && $payload) {
                $data = (isset($payload['success']) && array_key_exists('data', $payload) && is_array($payload['data']))
                    ? $payload['data']
                    : $payload;

                $items = isset($data['items']) && is_array($data['items']) ? $data['items'] : [];

                if ($q !== '') {
                    $qLower = mb_strtolower($q, 'UTF-8');
                    $items = array_values(array_filter($items, function ($u) use ($qLower) {
                        $ci = isset($u['ci']) ? mb_strtolower((string)$u['ci'], 'UTF-8') : '';
                        $nombre = isset($u['nombre_completo']) ? mb_strtolower((string)$u['nombre_completo'], 'UTF-8') : '';
                        $rol = isset($u['rol_nombre']) ? mb_strtolower((string)$u['rol_nombre'], 'UTF-8') : '';
                        $rolCodigo = isset($u['rol_codigo']) ? mb_strtolower((string)$u['rol_codigo'], 'UTF-8') : '';
                        return (strpos($ci, $qLower) !== false)
                            || (strpos($nombre, $qLower) !== false)
                            || (strpos($rol, $qLower) !== false)
                            || (strpos($rolCodigo, $qLower) !== false);
                    }));
                }

                $total = count($items);
                $totalPages = (int)ceil($total / $perPage);
                if ($totalPages < 1) {
                    $totalPages = 1;
                }
                if ($page > $totalPages) {
                    $page = $totalPages;
                }

                $offset = ($page - 1) * $perPage;
                $usuarios['items'] = array_slice($items, $offset, $perPage);
                $usuarios['pagination'] = [
                    'page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'total_pages' => $totalPages,
                ];
            } else {
                $message = 'No se pudieron cargar los usuarios.';
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

        $roles = [];
        $institutos = [];

        try {
            $rolesResponse = $this->catalogoService->roles();
            $rolesPayload = isset($rolesResponse['data']) && is_array($rolesResponse['data']) ? $rolesResponse['data'] : null;
            if (!empty($rolesResponse['success']) && $rolesPayload) {
                $rolesData = (isset($rolesPayload['success']) && array_key_exists('data', $rolesPayload) && is_array($rolesPayload['data']))
                    ? $rolesPayload['data']
                    : $rolesPayload;
                if (is_array($rolesData)) {
                    $roles = $rolesData;
                }
            }
        } catch (\Exception $e) {
            $roles = [];
        }

        // Solo SUPER_ADMIN necesita selector de institutos
        if ($actorRol === 'SUPER_ADMIN') {
            try {
                $instResponse = $this->catalogoService->institutos();
                $instPayload = isset($instResponse['data']) && is_array($instResponse['data']) ? $instResponse['data'] : null;
                if (!empty($instResponse['success']) && $instPayload) {
                    $instData = (isset($instPayload['success']) && array_key_exists('data', $instPayload) && is_array($instPayload['data']))
                        ? $instPayload['data']
                        : $instPayload;
                    if (is_array($instData)) {
                        $institutos = $instData;
                    }
                }
            } catch (\Exception $e) {
                $institutos = [];
            }
        }

        $this->view('admin/users', [
            'title' => 'Gestión de Usuarios | Admin',
            'current_page' => 'users',
            'usuarios' => $usuarios,
            'roles' => $roles,
            'institutos' => $institutos,
            'apiError' => $apiError,
            'filters' => [
                'q' => $q,
                'page' => $page,
                'per_page' => $perPage,
            ],
            'actorRol' => $actorRol,
            'flash' => $flash,
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
        $this->requireSuperAdmin();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $resource = isset($_GET['resource']) ? trim((string)$_GET['resource']) : '';
        if ($resource === '') {
            $resource = 'nacionalidad';
        }

        $institutoId = isset($_GET['instituto_id']) && is_numeric($_GET['instituto_id']) ? (int)$_GET['instituto_id'] : null;
        $editId = isset($_GET['edit_id']) && is_numeric($_GET['edit_id']) ? (int)$_GET['edit_id'] : null;

        $flash = null;
        if (!empty($_SESSION['flash_message'])) {
            $flash = [
                'type' => isset($_SESSION['flash_type']) ? (string)$_SESSION['flash_type'] : 'info',
                'message' => (string)$_SESSION['flash_message'],
                'errors' => isset($_SESSION['flash_errors']) && is_array($_SESSION['flash_errors']) ? $_SESSION['flash_errors'] : [],
            ];
        }
        unset($_SESSION['flash_type'], $_SESSION['flash_message'], $_SESSION['flash_errors']);

        $catalogosMenu = [];
        $catalogoItems = [];
        $catalogoLabel = $resource;
        $apiError = null;
        $institutos = [];
        $currentTenantScoped = false;
        $editItem = null;
        $carreraActivosMap = [];

        try {
            // Menú dinámico desde backend
            $menuResponse = $this->catalogoService->catalogos();
            $menuPayload = isset($menuResponse['data']) && is_array($menuResponse['data']) ? $menuResponse['data'] : null;
            if (!empty($menuResponse['success']) && $menuPayload) {
                $menuData = (isset($menuPayload['success']) && array_key_exists('data', $menuPayload) && is_array($menuPayload['data']))
                    ? $menuPayload['data']
                    : $menuPayload;

                if (is_array($menuData)) {
                    $catalogosMenu = $menuData;
                }
            }

            // Si el resource no existe en el menú, usamos el primero disponible
            $allowedResources = [];
            foreach ($catalogosMenu as $item) {
                if (is_array($item) && isset($item['resource'])) {
                    $allowedResources[] = (string)$item['resource'];
                }
            }

            if (!empty($allowedResources) && !in_array($resource, $allowedResources, true)) {
                $resource = $allowedResources[0];
            }

            // Label para el título
            foreach ($catalogosMenu as $item) {
                if (is_array($item) && isset($item['resource']) && (string)$item['resource'] === $resource) {
                    if (isset($item['label']) && is_string($item['label']) && trim($item['label']) !== '') {
                        $catalogoLabel = $item['label'];
                    }
                    if (!empty($item['tenant_scoped'])) {
                        $currentTenantScoped = true;
                    }
                    break;
                }
            }

            // Lista de sedes (institutos) para selector
            $instResponse = $this->catalogoService->catalogoAdmin('instituto');
            $instPayload = isset($instResponse['data']) && is_array($instResponse['data']) ? $instResponse['data'] : null;
            if (!empty($instResponse['success']) && $instPayload) {
                $instData = (isset($instPayload['success']) && array_key_exists('data', $instPayload))
                    ? $instPayload['data']
                    : $instPayload;

                if (is_array($instData)) {
                    foreach ($instData as $inst) {
                        if (is_array($inst) && !empty($inst['activo']) && isset($inst['id'])) {
                            $institutos[] = $inst;
                        }
                    }
                }
            }

            if ($currentTenantScoped && empty($institutoId) && !empty($institutos) && isset($institutos[0]['id'])) {
                $institutoId = (int)$institutos[0]['id'];
            }

            // Para Carreras: mapa de sedes activas (Instituto_Carrera) para marcar checks correctamente en el modal
            if ($resource === 'carrera') {
                $mapResp = $this->catalogoService->carreraActivos();
                $mapPayload = isset($mapResp['data']) && is_array($mapResp['data']) ? $mapResp['data'] : null;
                if (!empty($mapResp['success']) && is_array($mapPayload)) {
                    $mapData = (isset($mapPayload['success']) && array_key_exists('data', $mapPayload) && is_array($mapPayload['data']))
                        ? $mapPayload['data']
                        : $mapPayload;
                    if (is_array($mapData)) {
                        $carreraActivosMap = $mapData;
                    }
                }
            }

            // Datos del catálogo seleccionado (admin: incluye inactivos)
            $params = [];
            if ($currentTenantScoped && !empty($institutoId)) {
                $params['instituto_id'] = (int)$institutoId;
            }
            $dataResponse = $this->catalogoService->catalogoAdmin($resource, $params);
            $dataPayload = isset($dataResponse['data']) && is_array($dataResponse['data']) ? $dataResponse['data'] : null;
            if (!empty($dataResponse['success']) && $dataPayload) {
                $data = (isset($dataPayload['success']) && array_key_exists('data', $dataPayload))
                    ? $dataPayload['data']
                    : $dataPayload;

                if (is_array($data)) {
                    $catalogoItems = $data;
                }
            } else {
                $message = 'No se pudo cargar el catálogo.';
                if (is_array($dataPayload) && isset($dataPayload['message']) && is_string($dataPayload['message']) && trim($dataPayload['message']) !== '') {
                    $message = $dataPayload['message'];
                }
                $apiError = [
                    'status' => isset($dataResponse['status']) ? (int)$dataResponse['status'] : 0,
                    'message' => $message,
                ];
            }

            if (!empty($editId) && !empty($catalogoItems)) {
                foreach ($catalogoItems as $it) {
                    if (is_array($it) && isset($it['id']) && (int)$it['id'] === (int)$editId) {
                        $editItem = $it;
                        break;
                    }
                }
            }
        } catch (\Exception $e) {
            $apiError = [
                'status' => 0,
                'message' => 'Error de conexión con el servidor: ' . $e->getMessage(),
            ];
        }

        $this->view('admin/catalogs', [
            'title' => 'Gestión de Catálogos | Admin',
            'current_page' => 'catalogs',
            'flash' => $flash,
            'catalogosMenu' => $catalogosMenu,
            'resource' => $resource,
            'institutos' => $institutos,
            'institutoId' => $institutoId,
            'currentTenantScoped' => $currentTenantScoped,
            'editId' => $editId,
            'editItem' => $editItem,
            'carreraActivosMap' => $carreraActivosMap,
            'catalogoLabel' => $catalogoLabel,
            'catalogoItems' => $catalogoItems,
            'apiError' => $apiError,
        ], 'admin');
    }
}
