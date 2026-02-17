<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Tugas;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTugasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mst_guru_mapel_id' => ['sometimes', 'integer', 'exists:mst_guru_mapel,id'],
            'mst_kelas_id' => ['sometimes', 'integer', 'exists:mst_kelas,id'],
            'judul' => ['sometimes', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'file_lampiran' => ['nullable', 'string', 'max:255'],
            'tenggat_waktu' => ['sometimes', 'date'],
            'status' => ['nullable', 'integer', 'min:0', 'max:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'mst_guru_mapel_id.integer' => 'Guru mapel tidak valid',
            'mst_guru_mapel_id.exists' => 'Guru mapel tidak ditemukan',
            'mst_kelas_id.integer' => 'Kelas tidak valid',
            'mst_kelas_id.exists' => 'Kelas tidak ditemukan',
            'judul.string' => 'Judul harus berupa teks',
            'judul.max' => 'Judul maksimal 255 karakter',
            'tenggat_waktu.date' => 'Format tanggal tidak valid',
            'status.integer' => 'Status tidak valid',
            'status.min' => 'Status tidak valid',
            'status.max' => 'Status tidak valid',
        ];
    }
}
