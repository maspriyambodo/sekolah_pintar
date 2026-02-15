<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\AbsensiSiswa;

use Illuminate\Foundation\Http\FormRequest;

class CreateAbsensiSiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mst_siswa_id' => ['required', 'integer', 'exists:mst_siswa,id'],
            'tanggal' => ['required', 'date'],
            'status' => ['required', 'integer', 'min:1', 'max:4'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'mst_siswa_id.required' => 'Siswa ID wajib diisi',
            'mst_siswa_id.exists' => 'Siswa tidak ditemukan',
            'tanggal.required' => 'Tanggal wajib diisi',
            'tanggal.date' => 'Tanggal tidak valid',
            'status.required' => 'Status wajib diisi',
            'status.integer' => 'Status harus berupa angka',
            'status.min' => 'Status tidak valid',
            'status.max' => 'Status tidak valid',
        ];
    }
}
