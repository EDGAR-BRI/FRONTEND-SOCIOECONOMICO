<?php

namespace App\Services;

/**
 * ApiService - Servicio base para consumir APIs externas
 */
class ApiService
{
    private $baseUrl;
    private $headers = [];
    private $timeout = 30;

    /**
     * Constructor
     * 
     * @param string $baseUrl URL base de la API
     */
    public function __construct($baseUrl = null)
    {
        $this->baseUrl = $baseUrl ?: API_BASE_URL;
    }

    /**
     * Establece un header personalizado
     * 
     * @param string $key Nombre del header
     * @param string $value Valor del header
     */
    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }

    /**
     * Establece el timeout de las peticiones
     * 
     * @param int $seconds Segundos de timeout
     */
    public function setTimeout($seconds)
    {
        $this->timeout = $seconds;
    }

    /**
     * Realiza una petición GET
     * 
     * @param string $endpoint Endpoint de la API
     * @param array $params Parámetros query string
     * @return array Respuesta de la API
     */
    public function get($endpoint, $params = [])
    {
        $url = $this->buildUrl($endpoint, $params);
        return $this->request('GET', $url);
    }

    /**
     * Realiza una petición POST
     * 
     * @param string $endpoint Endpoint de la API
     * @param array $data Datos a enviar
     * @return array Respuesta de la API
     */
    public function post($endpoint, $data = [])
    {
        $url = $this->buildUrl($endpoint);
        return $this->request('POST', $url, $data);
    }

    /**
     * Realiza una petición PUT
     * 
     * @param string $endpoint Endpoint de la API
     * @param array $data Datos a enviar
     * @return array Respuesta de la API
     */
    public function put($endpoint, $data = [])
    {
        $url = $this->buildUrl($endpoint);
        return $this->request('PUT', $url, $data);
    }

    /**
     * Realiza una petición DELETE
     * 
     * @param string $endpoint Endpoint de la API
     * @return array Respuesta de la API
     */
    public function delete($endpoint)
    {
        $url = $this->buildUrl($endpoint);
        return $this->request('DELETE', $url);
    }

    /**
     * Construye la URL completa
     * 
     * @param string $endpoint Endpoint
     * @param array $params Parámetros query string
     * @return string URL completa
     */
    private function buildUrl($endpoint, $params = [])
    {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');

        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        return $url;
    }

    /**
     * Realiza la petición HTTP usando cURL
     * 
     * @param string $method Método HTTP
     * @param string $url URL completa
     * @param array $data Datos a enviar
     * @return array Respuesta parseada
     */
    private function request($method, $url, $data = null)
    {
        $ch = curl_init();

        // Configurar opciones de cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        // Configurar headers
        $headers = ['Content-Type: application/json'];
        foreach ($this->headers as $key => $value) {
            $headers[] = "{$key}: {$value}";
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Si hay datos, enviarlos como JSON
        if ($data !== null && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        // Ejecutar petición
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);

        // Manejar errores de cURL
        if ($error) {
            throw new \Exception("Error en petición cURL: {$error}");
        }

        // Parsear respuesta JSON
        $parsedResponse = json_decode($response, true);

        // Retornar respuesta con código HTTP
        return [
            'status' => $httpCode,
            'data' => $parsedResponse,
            'success' => $httpCode >= 200 && $httpCode < 300
        ];
    }
}
