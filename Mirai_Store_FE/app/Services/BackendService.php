<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class BackendService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('api.backend_url');
    }

    
    protected function getHeaders()
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $token = Session::get('auth_token');
        if ($token) {
            $headers['Authorization'] = "Bearer {$token}";
        }

        return $headers;
    }

    public function post($endpoint, $data = [])
    {
        return Http::withHeaders($this->getHeaders())
            ->post("{$this->baseUrl}/{$endpoint}", $data);
    }

    public function get($endpoint, $query = [])
    {
        return Http::withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/{$endpoint}", $query);
    }

    public function delete($endpoint, $data = [])
    {
        return Http::withHeaders($this->getHeaders())
            ->delete("{$this->baseUrl}/{$endpoint}", $data);
    }

    
}
