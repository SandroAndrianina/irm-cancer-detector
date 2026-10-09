<?php
namespace App\Application;

use App\Domain\Scan\Scan;
use App\Infrastructure\File\FileScanRepository;
use App\Infrastructure\Http\JavaApiClient;

class ScanService
{
    public function __construct(
        private JavaApiClient      $java,
        private FileScanRepository $fileRepo
    ) {}

    public function syncFromJava(): array
    {
        $raw   = $this->java->getScans();
        $scans = array_map(fn(array $row) => Scan::fromArray($row), $raw);
        $this->fileRepo->saveAll($scans);
        return $scans;
    }

    /**
     * Flow B from the UI: image -> intensity -> Java -> MySQL -> CI4 log.
     * @return array<string,mixed> the saved scan (JSON from Java)
     */
    public function createFromImage(array $meta, string $tmpPath): array
    {
        $intensity = (new ImageIntensityService())->meanIntensity($tmpPath);

        return $this->java->createScan([
            'patientRef'      => $meta['patientRef'],
            'zone'            => $meta['zone'],
            'contrastUsed'    => (bool) $meta['contrastUsed'],
            'signalIntensity' => $intensity,
        ]);
    }
}