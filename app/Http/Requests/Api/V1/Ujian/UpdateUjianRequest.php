<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Ujian;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUjianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mst_mapel_id' => ['nullable', 'integer', 'exists:mst_mapel,id'],
            'mst_kelas_id' => ['nullable', 'integer', 'exists:mst_kelas,id'],
            'jenis' => ['nullable', 'integer', 'min:1', 'max:5'],
            'nama' => ['nullable', 'string', 'max:100'],
            'tanggal' => ['nullable', 'date'],
            'semester' => ['nullable', 'integer', 'min:1', 'max:2'],
            'tahun_ajaran' => ['nullable', 'string', 'max:20'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'jenis.integer' => 'Jenis ujian harus berupa angka',
            'jenis.min' => 'Jenis ujian tidak valid',
            'jenis.max' => 'Jenis ujian tidak valid',
            'semester.integer' => 'Semester harus berupa angka',
            'semester.min' => 'Semester tidak valid',
            'semester.max' => 'Semester tidak valid',
        ];
    }
}
