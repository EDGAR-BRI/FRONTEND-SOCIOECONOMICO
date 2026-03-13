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
    public function index($sede = '')
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!empty($sede)) {
            $_SESSION['sede_actual'] = $sede;
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
            'catalogos' => $catalogos,
            'sede' => isset($_SESSION['sede_actual']) ? $_SESSION['sede_actual'] : ''
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
            'nacionalidad' => '/catalogo/nacionalidad',
            'sexo' => '/catalogo/sexo',
            'tipo_estudiante' => '/catalogo/tipo-estudiante',
            'carrera' => '/catalogo/carrera',
            'semestre' => '/catalogo/semestre',
            'estado_civil' => '/catalogo/estado-civil',
            'condicion_laboral' => '/catalogo/condicion-laboral',
            'relacion_laboral' => '/catalogo/relacion-laboral',
            'tipo_organizacion' => '/catalogo/tipo-organizacion',
            'sector_trabajo' => '/catalogo/sector-trabajo',
            'categoria_ocupacional' => '/catalogo/categoria-ocupacional',
            'tipo_convivencia' => '/catalogo/tipo-convivencia',
            'tipo_vivienda' => '/catalogo/tipo-vivienda',
            'tenencia_vivienda' => '/catalogo/tenencia-vivienda',
            'ambiente_vivienda' => '/catalogo/ambiente-vivienda',
            'activo_vivienda' => '/catalogo/activo-vivienda',
            'servicio_vivienda' => '/catalogo/servicio-vivienda',
            'frecuencia_agua' => '/catalogo/frecuencia-agua',
            'frecuencia_aseo' => '/catalogo/frecuencia-aseo',
            'frecuencia_electricidad' => '/catalogo/frecuencia-electricidad',
            'frecuencia_gas' => '/catalogo/frecuencia-gas',
            'transporte' => '/catalogo/transporte',
            'dependencia_economica' => '/catalogo/dependencia-economica',
            'fuente_ingreso' => '/catalogo/fuente-ingreso',
            'ingreso_familiar' => '/catalogo/ingreso-familiar',
            'nivel_educacion' => '/catalogo/nivel-educacion',
            'tipo_empresa' => '/catalogo/tipo-empresa',
            'veracidad' => '/catalogo/veracidad',
            'tipo_beca' => '/catalogo/tipo-beca',
        ];

        // Cargar cada catálogo
        foreach ($endpoints as $key => $endpoint) {
            try {
                $response = $this->apiService->get($endpoint);
                if ($response['success']) {
                    $payload = isset($response['data']) ? $response['data'] : null;
                    $catalogos[$key] = $this->extraerDataCatalogo($payload);
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

    private function extraerDataCatalogo($payload)
    {
        if (!is_array($payload)) {
            return [];
        }

        // Formato estándar: {success, data, message}
        if (array_key_exists('success', $payload) && array_key_exists('data', $payload)) {
            return $payload['data'];
        }

        // Formato legacy: array plano
        return $payload;
    }

    /**
     * Procesa el envío del formulario
     */
    public function submit($sede = '')
    {

        if (!$this->isPost()) {
            $redirectPath = !empty($sede) ? "/{$sede}/formulario" : '/';
            $this->redirect($redirectPath);
            return;
        }

        // Unir prefijo y teléfono si ambos existen
        if (isset($_POST['prefijo']) && isset($_POST['telefono'])) {
            $_POST['telefono'] = $_POST['prefijo'] . $_POST['telefono'];
            unset($_POST['prefijo']);
        }

        // Crear modelo con datos del formulario
        $encuesta = new Encuesta($_POST);

        // Agregamos la sede al modelo
        if (!empty($sede)) {
            $encuesta->set('sede', $sede); // suponiendo que el backend reciba la sede o la enviemos
        }

        // Validar datos
        if (!$encuesta->validate()) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['errors'] = $encuesta->getErrors();
            $_SESSION['form_data'] = $_POST;
            $redirectPath = !empty($sede) ? "/{$sede}/formulario" : '/';
            $this->redirect($redirectPath);
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
                $redirectPath = !empty($sede) ? "/{$sede}/formulario" : '/';
                $this->redirect($redirectPath);
            }
        } catch (\Exception $e) {
            // Manejar excepción
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['errors'] = ['general' => 'Error de conexión con el servidor: ' . $e->getMessage()];
            $_SESSION['form_data'] = $_POST;
            $redirectPath = !empty($sede) ? "/{$sede}/formulario" : '/';
            $this->redirect($redirectPath);
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
