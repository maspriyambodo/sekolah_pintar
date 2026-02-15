<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Ujian;

use Illuminate\Foundation\Http\FormRequest;

class CreateUjianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mst_mapel_id' => ['required', 'integer', 'exists:mst_mapel,id'],
            'mst_kelas_id' => ['required', 'integer', 'exists:mst_kelas,id'],
            'jenis' => ['required', 'integer', 'min:1', 'max:5'],
            'nama' => ['required', 'string', 'max:100'],
            'tanggal' => ['required', 'date'],
            'semester' => ['required', 'integer', 'min:1', 'max:2'],
            'tahun_ajaran' => ['required', 'string', 'max:20'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'mst_mapel_id.required' => 'Mapel wajib dipilih',
            'mst_mapel_id.exists' => 'Mapel tidak ditemukan',
            'mst_kelas_id.required' => 'Kelas wajib dipilih',
            'mst_kelas_id.exists' => 'Kelas tidak ditemukan',
            'jenis.integer' => 'Jenis ujian harus berupa angka',
            'jenis.min' => 'Jenis ujian tidak valid',
            'jenis.max' => 'Jenis ujian tidak valid',
            'semester.integer' => 'Semester harus berupa angka',
            'semester.min' => 'Semester tidak valid',
            'semester.max' => 'Semester tidak valid',
        ];
    }
}
