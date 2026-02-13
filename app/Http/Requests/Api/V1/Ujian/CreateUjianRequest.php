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
            'jenis' => ['required', 'string', 'in:uts,uas,ulangan_harian,ulangan_bulanan,praktik'],
            'nama' => ['required', 'string', 'max:100'],
            'tanggal' => ['required', 'date'],
            'semester' => ['required', 'string', 'max:10'],
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
            'jenis.in' => 'Jenis ujian tidak valid',
        ];
    }
}
