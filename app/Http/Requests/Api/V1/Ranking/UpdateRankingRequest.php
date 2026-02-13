<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Ranking;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRankingRequest extends FormRequest
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
            'rata_rata_nilai' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'peringkat' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
