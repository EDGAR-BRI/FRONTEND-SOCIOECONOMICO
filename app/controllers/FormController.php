<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Encuesta;
use App\Services\ApiService;

/**
 * FormController - Controlador para el formulario socioeconómico
 */
class FormController extends Controller
{
    private $apiService;

    public function __construct()
    {
        $this->apiService = new ApiService();
    }

    /**
     * Muestra el formulario con todos los catálogos
     */
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Obtener errores y datos antiguos de sesión
        $errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
        $oldData = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];

        // Limpiar sesión
        unset($_SESSION['errors']);
        unset($_SESSION['form_data']);

        // Cargar todos los catálogos desde la API
        $catalogos = $this->cargarCatalogos();

        $this->view('form/index', [
            'errors' => $errors,
            'old' => $oldData,
            'catalogos' => $catalogos
        ]);
    }

    /**
     * Carga todos los catálogos desde la API
     */
    private function cargarCatalogos()
    {
        $catalogos = [];

        // Lista de todos los catálogos necesarios
        // El backend usa la ruta dinámica /catalogo/:resource
        $endpoints = [
            'nacionalidades' => '/catalogo/nacionalidades',
            'sexos' => '/catalogo/sexos',
            'tipos_estudiante' => '/catalogo/tipos-estudiante',
            'carreras' => '/catalogo/carreras',
            'semestres' => '/catalogo/semestres',
            'estados_civiles' => '/catalogo/estados-civiles',
            'condiciones_laborales' => '/catalogo/condiciones-laborales',
            'relaciones_laborales' => '/catalogo/relaciones-laborales',
            'tipos_organizacion' => '/catalogo/tipos-organizacion',
            'sectores_trabajo' => '/catalogo/sectores-trabajo',
            'categorias_ocupacionales' => '/catalogo/categorias-ocupacionales',
            'tipos_convivencia' => '/catalogo/tipos-convivencia',
            'tipos_vivienda' => '/catalogo/tipos-vivienda',
            'tenencias_vivienda' => '/catalogo/tenencias-vivienda',
            'ambientes_vivienda' => '/catalogo/ambientes-vivienda',
            'activos_vivienda' => '/catalogo/activos-vivienda',
            'servicios_vivienda' => '/catalogo/servicios-vivienda',
            'frecuencias_agua' => '/catalogo/frecuencias-servicio-agua',
            'frecuencias_aseo' => '/catalogo/frecuencias-servicio-aseo',
            'frecuencias_electricidad' => '/catalogo/frecuencias-servicio-electricidad',
            'frecuencias_gas' => '/catalogo/frecuencias-servicio-gas',
            'transportes' => '/catalogo/transportes',
            'dependencias_economicas' => '/catalogo/dependencias-economicas',
            'fuentes_ingreso' => '/catalogo/fuentes-ingreso-familiar',
            'ingresos_familiares' => '/catalogo/ingresos-familiares',
            'niveles_educacion' => '/catalogo/niveles-educacion',
            'tipos_empresa' => '/catalogo/tipos-empresa',
            'veracidades' => '/catalogo/veracidades',
            'tipos_beca' => '/catalogo/tipos-beca',
        ];

        // Cargar cada catálogo
        foreach ($endpoints as $key => $endpoint) {
            try {
                $response = $this->apiService->get($endpoint);
                if ($response['success']) {
                    $catalogos[$key] = $response['data'];
                } else {
                    // Si falla, usar array vacío
                    $catalogos[$key] = [];
                }
            } catch (\Exception $e) {
                // En caso de error, usar array vacío
                $catalogos[$key] = [];
            }
        }

        return $catalogos;
    }

    /**
     * Procesa el envío del formulario
     */
    public function submit()
    {
        if (!$this->isPost()) {
            $this->redirect('/');
            return;
        }

        // Crear modelo con datos del formulario
        $encuesta = new Encuesta($_POST);

        // Validar datos
        if (!$encuesta->validate()) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['errors'] = $encuesta->getErrors();
            $_SESSION['form_data'] = $_POST;
            $this->redirect('/');
            return;
        }

        try {
            // Enviar datos a la API
            $response = $this->apiService->post('/encuesta', $encuesta->toArray());

            if ($response['success']) {
                // Guardar datos de la encuesta en sesión para mostrar en success
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['encuesta_enviada'] = [
                    'nombres' => $encuesta->get('nombres'),
                    'apellidos' => $encuesta->get('apellidos'),
                    'cedula' => $encuesta->get('cedula')
                ];

                $this->redirect('/success');
            } else {
                // Manejar error de la API
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $errorMsg = isset($response['data']['message'])
                    ? $response['data']['message']
                    : 'Error al procesar el formulario. Intente nuevamente.';
                $_SESSION['errors'] = ['general' => $errorMsg];
                $_SESSION['form_data'] = $_POST;
                $this->redirect('/');
            }
        } catch (\Exception $e) {
            // Manejar excepción
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['errors'] = ['general' => 'Error de conexión con el servidor: ' . $e->getMessage()];
            $_SESSION['form_data'] = $_POST;
            $this->redirect('/');
        }
    }

    /**
     * Muestra la página de éxito
     */
    public function success()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $encuestaData = isset($_SESSION['encuesta_enviada']) ? $_SESSION['encuesta_enviada'] : null;
        unset($_SESSION['encuesta_enviada']);

        if (!$encuestaData) {
            $this->redirect('/');
            return;
        }

        $this->view('form/success', [
            'encuesta' => $encuestaData
        ]);
    }
}
