<?php
namespace App\Infrastructure\Http;

use Config\Services;

class JavaApiClient
{
    private string $base;

    public function __construct()
    {
        $this->base = rtrim(env('JAVA_BASE_URL', 'http://java-service:8080'), '/');
    }

    public function getScans(): array
    {
        $client = Services::curlrequest();
        $res    = $client->get($this->base . '/api/scans', ['timeout' => 5]);
        return json_decode((string) $res->getBody(), true) ?? [];
    }

    public function createScan(array $payload): array
    {
        $client = Services::curlrequest();
        $res    = $client->post($this->base . '/api/scans', [
            'json'    => $payload,
            'timeout' => 5,
        ]);
        return json_decode((string) $res->getBody(), true) ?? [];
    }
}