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
        session_start();

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
        // TEMPORAL: Deshabilitado hasta que la API esté configurada
        // Para evitar timeouts, retornamos arrays vacíos
        // Descomentar cuando la API esté lista

        return [
            'nacionalidades' => [],
            'sexos' => [],
            'tipos_estudiante' => [],
            'carreras' => [],
            'semestres' => [],
            'estados_civiles' => [],
            'condiciones_laborales' => [],
            'relaciones_laborales' => [],
            'tipos_organizacion' => [],
            'sectores_trabajo' => [],
            'categorias_ocupacionales' => [],
            'tipos_convivencia' => [],
            'tipos_vivienda' => [],
            'tenencias_vivienda' => [],
            'ambientes_vivienda' => [],
            'activos_vivienda' => [],
            'servicios_vivienda' => [],
            'frecuencias_agua' => [],
            'frecuencias_aseo' => [],
            'frecuencias_electricidad' => [],
            'frecuencias_gas' => [],
            'transportes' => [],
            'dependencias_economicas' => [],
            'fuentes_ingreso' => [],
            'ingresos_familiares' => [],
            'niveles_educacion' => [],
            'tipos_empresa' => [],
            'veracidades' => [],
            'tipos_beca' => [],
        ];

        /* CÓDIGO ORIGINAL - Descomentar cuando la API esté lista
        $catalogos = [];

        // Lista de todos los catálogos necesarios
        $endpoints = [
            'nacionalidades' => '/nacionalidades',
            'sexos' => '/sexos',
            'tipos_estudiante' => '/tipos-estudiante',
            'carreras' => '/carreras',
            'semestres' => '/semestres',
            'estados_civiles' => '/estados-civiles',
            'condiciones_laborales' => '/condiciones-laborales',
            'relaciones_laborales' => '/relaciones-laborales',
            'tipos_organizacion' => '/tipos-organizacion',
            'sectores_trabajo' => '/sectores-trabajo',
            'categorias_ocupacionales' => '/categorias-ocupacionales',
            'tipos_convivencia' => '/tipos-convivencia',
            'tipos_vivienda' => '/tipos-vivienda',
            'tenencias_vivienda' => '/tenencias-vivienda',
            'ambientes_vivienda' => '/ambientes-vivienda',
            'activos_vivienda' => '/activos-vivienda',
            'servicios_vivienda' => '/servicios-vivienda',
            'frecuencias_agua' => '/frecuencias-servicio-agua',
            'frecuencias_aseo' => '/frecuencias-servicio-aseo',
            'frecuencias_electricidad' => '/frecuencias-servicio-electricidad',
            'frecuencias_gas' => '/frecuencias-servicio-gas',
            'transportes' => '/transportes',
            'dependencias_economicas' => '/dependencias-economicas',
            'fuentes_ingreso' => '/fuentes-ingreso-familiar',
            'ingresos_familiares' => '/ingresos-familiares',
            'niveles_educacion' => '/niveles-educacion',
            'tipos_empresa' => '/tipos-empresa',
            'veracidades' => '/veracidades',
            'tipos_beca' => '/tipos-beca',
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
        */
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
            session_start();
            $_SESSION['errors'] = $encuesta->getErrors();
            $_SESSION['form_data'] = $_POST;
            $this->redirect('/');
            return;
        }

        try {
            // Enviar datos a la API
            $response = $this->apiService->post('/encuestas', $encuesta->toArray());

            if ($response['success']) {
                // Guardar datos de la encuesta en sesión para mostrar en success
                session_start();
                $_SESSION['encuesta_enviada'] = [
                    'nombres' => $encuesta->get('nombres'),
                    'apellidos' => $encuesta->get('apellidos'),
                    'cedula' => $encuesta->get('cedula')
                ];

                $this->redirect('/success');
            } else {
                // Manejar error de la API
                session_start();
                $errorMsg = isset($response['data']['message'])
                    ? $response['data']['message']
                    : 'Error al procesar el formulario. Intente nuevamente.';
                $_SESSION['errors'] = ['general' => $errorMsg];
                $_SESSION['form_data'] = $_POST;
                $this->redirect('/');
            }
        } catch (\Exception $e) {
            // Manejar excepción
            session_start();
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
        session_start();
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
