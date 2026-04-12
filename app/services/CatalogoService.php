<?php

namespace App\Services;

class CatalogoService
{
    private $api;

    public function __construct(ApiService $api = null)
    {
        $this->api = $api ?: new ApiService();
    }

    public function roles()
    {
        return $this->api->get('/catalogo/rol');
    }

    public function institutos()
    {
        return $this->api->get('/catalogo/instituto');
    }

    public function catalogos()
    {
        return $this->api->get('/catalogo');
    }

    public function catalogo($resource, $params = [])
    {
        $resource = trim((string)$resource);
        return $this->api->get('/catalogo/' . rawurlencode($resource), $params);
    }
}
