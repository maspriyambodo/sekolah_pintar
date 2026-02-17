<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1\Spk;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpkKriteriaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kode_kriteria' => $this->kode_kriteria,
            'nama_kriteria' => $this->nama_kriteria,
            'bobot' => (float) $this->bobot,
            'tipe' => $this->tipe,
            'tipe_label' => $this->tipe === 'benefit' ? 'Benefit' : 'Cost',
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
