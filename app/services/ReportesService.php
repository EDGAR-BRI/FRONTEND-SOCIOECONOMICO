<?php

namespace App\Services;

class ReportesService
{
    private $api;

    public function __construct(ApiService $api = null)
    {
        $this->api = $api ?: new ApiService();
    }

    public function getDashboardGeneral(array $params = [])
    {
        return $this->api->get('/reportes/dashboard-general', $params);
    }

    public function getAnalisisAcademico(array $params = [])
    {
        return $this->api->get('/reportes/analisis-academico', $params);
    }

    public function getDemograficoVulnerabilidad(array $params = [])
    {
        return $this->api->get('/reportes/demografico-vulnerabilidad', $params);
    }

    public function getFiltros(array $params = [])
    {
        return $this->api->get('/reportes/filtros', $params);
    }
}
