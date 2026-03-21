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
}
