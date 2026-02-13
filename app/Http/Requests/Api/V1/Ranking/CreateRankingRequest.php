<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Ranking;

use Illuminate\Foundation\Http\FormRequest;

class CreateRankingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mst_siswa_id' => ['required', 'integer', 'exists:mst_siswa,id'],
            'mst_kelas_id' => ['required', 'integer', 'exists:mst_kelas,id'],
            'semester' => ['required', 'string', 'max:10'],
            'tahun_ajaran' => ['required', 'string', 'max:20'],
            'rata_rata_nilai' => ['required', 'numeric', 'min:0', 'max:100'],
            'peringkat' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'mst_siswa_id.required' => 'Siswa wajib dipilih',
            'mst_siswa_id.exists' => 'Siswa tidak ditemukan',
            'mst_kelas_id.required' => 'Kelas wajib dipilih',
            'mst_kelas_id.exists' => 'Kelas tidak ditemukan',
        ];
    }
}
