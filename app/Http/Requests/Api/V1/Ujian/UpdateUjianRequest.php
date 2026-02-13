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
            'jenis' => ['nullable', 'string', 'in:uts,uas,ulangan_harian,ulangan_bulanan,praktik'],
            'nama' => ['nullable', 'string', 'max:100'],
            'tanggal' => ['nullable', 'date'],
            'semester' => ['nullable', 'string', 'max:10'],
            'tahun_ajaran' => ['nullable', 'string', 'max:20'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'jenis.in' => 'Jenis ujian tidak valid',
        ];
    }
}
