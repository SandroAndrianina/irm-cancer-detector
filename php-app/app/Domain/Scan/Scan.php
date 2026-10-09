<?php
namespace App\Domain\Scan;

/**
 * Pure domain entity. Mirrors the Java one.
 */
class Scan
{
    public ?int $id = null;
    public string $patientRef = '';
    public string $zone = 'brain';
    public bool $contrastUsed = false;
    public int $signalIntensity = 0;
    public string $resultat = 'en_attente';
    public string $createdAt = '';

    public function computeResultat(): string
    {
        if ($this->contrastUsed && $this->signalIntensity >= 70) return 'malin';
        if (!$this->contrastUsed || $this->signalIntensity < 40)  return 'benin';
        return 'en_attente';
    }

    public static function fromArray(array $a): self
    {
        $s = new self();
        $s->id              = $a['id']              ?? null;
        $s->patientRef      = $a['patientRef']      ?? '';
        $s->zone            = $a['zone']            ?? 'brain';
        $s->contrastUsed    = (bool)($a['contrastUsed'] ?? false);
        $s->signalIntensity = (int)($a['signalIntensity'] ?? 0);
        $s->resultat        = $a['resultat']        ?? 'en_attente';
        $s->createdAt       = $a['createdAt']       ?? '';
        return $s;
    }

    public function toArray(): array
    {
        return [
            'id'              => $this->id,
            'patientRef'      => $this->patientRef,
            'zone'            => $this->zone,
            'contrastUsed'    => $this->contrastUsed,
            'signalIntensity' => $this->signalIntensity,
            'resultat'        => $this->resultat,
            'createdAt'       => $this->createdAt,
        ];
    }
}