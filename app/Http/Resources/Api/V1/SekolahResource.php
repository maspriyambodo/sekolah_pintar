<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SekolahResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'npsn' => $this->npsn,
            'nama_sekolah' => $this->nama_sekolah,
            'alamat' => $this->alamat,
            'logo_path' => $this->logo_path,
            'is_active' => $this->is_active,
            'subscription_plan' => $this->subscription_plan,
            'settings' => $this->whenLoaded('settings', fn () => $this->settings->map(fn ($s) => [
                'key' => $s->key,
                'value' => $s->value,
            ])),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
