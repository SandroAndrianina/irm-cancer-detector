<?php
namespace App\Domain\Scan;

interface ScanRepositoryInterface
{
    /** @return Scan[] */
    public function findAll(): array;

    /** @param Scan[] $scans */
    public function saveAll(array $scans): void;
}