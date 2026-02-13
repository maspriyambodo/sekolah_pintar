<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Rapor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRaporRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mst_siswa_id' => ['nullable', 'integer', 'exists:mst_siswa,id'],
            'mst_kelas_id' => ['nullable', 'integer', 'exists:mst_kelas,id'],
            'semester' => ['nullable', 'string', 'max:10'],
            'tahun_ajaran' => ['nullable', 'string', 'max:20'],
            'catatan_wali' => ['nullable', 'string'],
            'sakit' => ['nullable', 'integer', 'min:0'],
            'izin' => ['nullable', 'integer', 'min:0'],
            'tanpa_keterangan' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
