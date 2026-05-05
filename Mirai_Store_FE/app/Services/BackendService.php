<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BackendService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('api.backend_url');
    }

    public function post($endpoint, $data = [])
    {
        return Http::post("{$this->baseUrl}/{$endpoint}", $data);
    }

    public function get($endpoint, $query = [])
    {
        return Http::get("{$this->baseUrl}/{$endpoint}", $query);
    }

    
}
