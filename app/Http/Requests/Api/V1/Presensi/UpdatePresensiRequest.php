<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Presensi;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePresensiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jam_masuk' => ['nullable', 'date_format:H:i:s'],
            'status' => ['sometimes', 'integer'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'jam_masuk.date_format' => 'Format jam tidak valid',
            'status.integer' => 'Status tidak valid',
            'keterangan.max' => 'Keterangan maksimal 255 karakter',
        ];
    }
}
