<?php
namespace App\Infrastructure\File;

use App\Domain\Scan\Scan;
use App\Domain\Scan\ScanRepositoryInterface;

/**
 * Stores scans as JSON in writable/data/scans.json.
 */
class FileScanRepository implements ScanRepositoryInterface
{
    private string $path;

    public function __construct()
    {
        $this->path = WRITEPATH . 'data/scans.json';
    }

    public function findAll(): array
    {
        if (!is_file($this->path)) return [];
        $raw = file_get_contents($this->path);
        $arr = json_decode($raw, true) ?: [];
        return array_map(fn($row) => Scan::fromArray($row), $arr);
    }

    public function saveAll(array $scans): void
    {
        $rows = array_map(fn(Scan $s) => $s->toArray(), $scans);
        file_put_contents($this->path, json_encode($rows, JSON_PRETTY_PRINT));
    }
}