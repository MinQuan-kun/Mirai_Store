<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class BackendService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('api.backend_url'), '/');

        if (!preg_match('/^https?:\/\//i', $this->baseUrl)) {
            $this->baseUrl = 'https://' . $this->baseUrl;
        }
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

    public function put($endpoint, $data = [])
    {
        return Http::withHeaders($this->getHeaders())
            ->put("{$this->baseUrl}/{$endpoint}", $data);
    }

    public function postForm($endpoint, $data = [])
    {
        return Http::withHeaders($this->getHeaders())
            ->asForm()
            ->post("{$this->baseUrl}/{$endpoint}", $data);
    }

    public function putForm($endpoint, $data = [])
    {
        return Http::withHeaders($this->getHeaders())
            ->asForm()
            ->put("{$this->baseUrl}/{$endpoint}", $data);
    }

    public function multipartPost($endpoint, $data = [], $fileField = null, $filePath = null, $fileName = null)
    {
        $headers = $this->getHeaders();
        unset($headers['Content-Type']); // Let the HTTP client set multipart/form-data
        
        $request = Http::withHeaders($headers);

        if ($fileField && $filePath) {
            $request = $request->attach($fileField, file_get_contents($filePath), $fileName ?? basename($filePath));
        }

        return $request->post("{$this->baseUrl}/{$endpoint}", $data);
    }

    public function multipartPut($endpoint, $data = [], $fileField = null, $filePath = null, $fileName = null)
    {
        $headers = $this->getHeaders();
        unset($headers['Content-Type']); // Let the HTTP client set multipart/form-data
        
        $request = Http::withHeaders($headers);

        if ($fileField && $filePath) {
            $request = $request->attach($fileField, file_get_contents($filePath), $fileName ?? basename($filePath));
        }

        return $request->put("{$this->baseUrl}/{$endpoint}", $data);
    }

    public function patch($endpoint, $data = [])
    {
        return Http::withHeaders($this->getHeaders())
            ->patch("{$this->baseUrl}/{$endpoint}", $data);
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
