<?php
namespace App\Controllers\Api;

use App\Application\ScanService;
use App\Controllers\BaseController;
use App\Infrastructure\File\FileScanRepository;
use App\Infrastructure\Http\JavaApiClient;

class Scan extends BaseController
{
    public function index()
    {
        try {
            $service = new ScanService(new JavaApiClient(), new FileScanRepository());
            $scans   = $service->syncFromJava();
            return $this->response->setJSON(array_map(fn($s) => $s->toArray(), $scans));
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(502)
                ->setJSON(['error' => $e->getMessage()]);
        }
    }

    public function create()
    {
        try {
            $file = $this->request->getFile('image');
            if (!$file || !$file->isValid()) {
                return $this->response->setStatusCode(400)
                    ->setJSON(['error' => 'image file required']);
            }

            $meta = [
                'patientRef'   => (string) $this->request->getPost('patientRef'),
                'zone'         => (string) $this->request->getPost('zone'),
                'contrastUsed' => (bool)   $this->request->getPost('contrastUsed'),
            ];

            if ($meta['patientRef'] === '') {
                return $this->response->setStatusCode(400)
                    ->setJSON(['error' => 'patientRef required']);
            }

            $service = new ScanService(new JavaApiClient(), new FileScanRepository());
            $saved   = $service->createFromImage($meta, $file->getTempName());

            return $this->response->setStatusCode(201)->setJSON($saved);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)
                ->setJSON(['error' => $e->getMessage()]);
        }
    }
}