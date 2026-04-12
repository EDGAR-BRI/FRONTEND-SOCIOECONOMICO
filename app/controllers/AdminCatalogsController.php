<?php

namespace App\Controllers;

use Core\Controller;
use App\Services\CatalogoService;

class AdminCatalogsController extends Controller
{
    private $catalogos;

    public function __construct()
    {
        $this->catalogos = new CatalogoService();
    }

    private function flash($type, $message, $errors = null)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['flash_type'] = (string)$type;
        $_SESSION['flash_message'] = (string)$message;
        if (is_array($errors)) {
            $_SESSION['flash_errors'] = $errors;
        } else {
            unset($_SESSION['flash_errors']);
        }
    }

    private function checkSuperAdmin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['auth_user']) || empty($_SESSION['auth_token'])) {
            $this->redirect(BASE_URL . '/login');
        }

        $rol = null;
        if (isset($_SESSION['auth_user']) && is_array($_SESSION['auth_user'])
            && isset($_SESSION['auth_user']['rol']) && is_array($_SESSION['auth_user']['rol'])
            && !empty($_SESSION['auth_user']['rol']['codigo'])
        ) {
            $rol = (string)$_SESSION['auth_user']['rol']['codigo'];
        }

        if ($rol !== 'SUPER_ADMIN') {
            $this->flash('error', 'No autorizado: solo SUPER_ADMIN puede gestionar catálogos.');
            $this->redirect(BASE_URL . '/admin');
        }
    }

    private function redirectBack($resource, $institutoId)
    {
        $qs = [];
        if ($resource !== '') {
            $qs['resource'] = $resource;
        }
        if (!empty($institutoId)) {
            $qs['instituto_id'] = (int)$institutoId;
        }

        $url = BASE_URL . '/admin/catalogos';
        if (!empty($qs)) {
            $url .= '?' . http_build_query($qs);
        }

        $this->redirect($url);
    }

    public function create()
    {
        $this->checkSuperAdmin();

        $resource = isset($_POST['resource']) ? trim((string)$_POST['resource']) : '';
        $institutoId = isset($_POST['instituto_id']) && is_numeric($_POST['instituto_id']) ? (int)$_POST['instituto_id'] : null;

        $data = $_POST;
        unset($data['resource']);

        try {
            $response = $this->catalogos->adminCreate($resource, $data);
            $payload = isset($response['data']) && is_array($response['data']) ? $response['data'] : null;

            if (!empty($response['success']) && $payload && !empty($payload['success'])) {
                $this->flash('success', 'Registro creado correctamente.');
            } else {
                $message = 'No se pudo crear el registro.';
                if ($payload && isset($payload['message']) && is_string($payload['message']) && trim($payload['message']) !== '') {
                    $message = $payload['message'];
                }
                $errors = $payload && isset($payload['data']['errors']) && is_array($payload['data']['errors']) ? $payload['data']['errors'] : null;
                $this->flash('error', $message, $errors);
            }
        } catch (\Exception $e) {
            $this->flash('error', 'Error de conexión con el servidor: ' . $e->getMessage());
        }

        $this->redirectBack($resource, $institutoId);
    }

    public function update($params = [])
    {
        $this->checkSuperAdmin();

        $id = isset($params['id']) ? (int)$params['id'] : 0;
        $resource = isset($_POST['resource']) ? trim((string)$_POST['resource']) : '';
        $institutoId = isset($_POST['instituto_id']) && is_numeric($_POST['instituto_id']) ? (int)$_POST['instituto_id'] : null;

        $data = $_POST;
        unset($data['resource']);

        try {
            $response = $this->catalogos->adminUpdate($resource, $id, $data);
            $payload = isset($response['data']) && is_array($response['data']) ? $response['data'] : null;

            if (!empty($response['success']) && $payload && !empty($payload['success'])) {
                $this->flash('success', 'Registro actualizado correctamente.');
            } else {
                $message = 'No se pudo actualizar el registro.';
                if ($payload && isset($payload['message']) && is_string($payload['message']) && trim($payload['message']) !== '') {
                    $message = $payload['message'];
                }
                $errors = $payload && isset($payload['data']['errors']) && is_array($payload['data']['errors']) ? $payload['data']['errors'] : null;
                $this->flash('error', $message, $errors);
            }
        } catch (\Exception $e) {
            $this->flash('error', 'Error de conexión con el servidor: ' . $e->getMessage());
        }

        $this->redirectBack($resource, $institutoId);
    }

    public function delete($params = [])
    {
        $this->checkSuperAdmin();

        $id = isset($params['id']) ? (int)$params['id'] : 0;
        $resource = isset($_POST['resource']) ? trim((string)$_POST['resource']) : '';
        $institutoId = isset($_POST['instituto_id']) && is_numeric($_POST['instituto_id']) ? (int)$_POST['instituto_id'] : null;

        try {
            $qs = [];
            if (!empty($institutoId)) {
                $qs['instituto_id'] = (int)$institutoId;
            }

            $response = $this->catalogos->adminDelete($resource, $id, $qs);
            $payload = isset($response['data']) && is_array($response['data']) ? $response['data'] : null;

            if (!empty($response['success']) && $payload && !empty($payload['success'])) {
                $this->flash('success', 'Registro desactivado correctamente.');
            } else {
                $message = 'No se pudo desactivar el registro.';
                if ($payload && isset($payload['message']) && is_string($payload['message']) && trim($payload['message']) !== '') {
                    $message = $payload['message'];
                }
                $errors = $payload && isset($payload['data']['errors']) && is_array($payload['data']['errors']) ? $payload['data']['errors'] : null;
                $this->flash('error', $message, $errors);
            }
        } catch (\Exception $e) {
            $this->flash('error', 'Error de conexión con el servidor: ' . $e->getMessage());
        }

        $this->redirectBack($resource, $institutoId);
    }

    public function restore($params = [])
    {
        $this->checkSuperAdmin();

        $id = isset($params['id']) ? (int)$params['id'] : 0;
        $resource = isset($_POST['resource']) ? trim((string)$_POST['resource']) : '';
        $institutoId = isset($_POST['instituto_id']) && is_numeric($_POST['instituto_id']) ? (int)$_POST['instituto_id'] : null;

        try {
            $data = [];
            if (!empty($institutoId)) {
                $data['instituto_id'] = (int)$institutoId;
            }

            $response = $this->catalogos->adminRestore($resource, $id, $data);
            $payload = isset($response['data']) && is_array($response['data']) ? $response['data'] : null;

            if (!empty($response['success']) && $payload && !empty($payload['success'])) {
                $this->flash('success', 'Registro restaurado correctamente.');
            } else {
                $message = 'No se pudo restaurar el registro.';
                if ($payload && isset($payload['message']) && is_string($payload['message']) && trim($payload['message']) !== '') {
                    $message = $payload['message'];
                }
                $errors = $payload && isset($payload['data']['errors']) && is_array($payload['data']['errors']) ? $payload['data']['errors'] : null;
                $this->flash('error', $message, $errors);
            }
        } catch (\Exception $e) {
            $this->flash('error', 'Error de conexión con el servidor: ' . $e->getMessage());
        }

        $this->redirectBack($resource, $institutoId);
    }
}
