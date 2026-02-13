<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\AbsensiSiswa;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAbsensiSiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mst_siswa_id' => ['nullable', 'integer', 'exists:mst_siswa,id'],
            'tanggal' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'in:hadir,izin,sakit,alpha'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'mst_siswa_id.exists' => 'Siswa tidak ditemukan',
            'tanggal.date' => 'Tanggal tidak valid',
            'status.in' => 'Status harus hadir, izin, sakit, atau alpha',
        ];
    }
}
