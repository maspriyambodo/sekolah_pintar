<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Presensi;

use Illuminate\Foundation\Http\FormRequest;

class CreatePresensiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mst_guru_mapel_id' => ['required', 'integer', 'exists:mst_guru_mapel,id'],
            'mst_siswa_id' => ['required', 'integer', 'exists:mst_siswa,id'],
            'tanggal' => ['required', 'date'],
            'jam_masuk' => ['nullable', 'date_format:H:i:s'],
            'status' => ['required', 'integer'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'mst_guru_mapel_id.required' => 'Guru mapel wajib dipilih',
            'mst_guru_mapel_id.integer' => 'Guru mapel tidak valid',
            'mst_guru_mapel_id.exists' => 'Guru mapel tidak ditemukan',
            'mst_siswa_id.required' => 'Siswa wajib dipilih',
            'mst_siswa_id.integer' => 'Siswa tidak valid',
            'mst_siswa_id.exists' => 'Siswa tidak ditemukan',
            'tanggal.required' => 'Tanggal wajib diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
            'jam_masuk.date_format' => 'Format jam tidak valid',
            'status.required' => 'Status wajib dipilih',
            'status.integer' => 'Status tidak valid',
            'keterangan.max' => 'Keterangan maksimal 255 karakter',
        ];
    }
}
