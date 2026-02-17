<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\TugasSiswa;

use Illuminate\Foundation\Http\FormRequest;

class CreateTugasSiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mst_tugas_id' => ['required', 'integer', 'exists:mst_tugas,id'],
            'mst_siswa_id' => ['required', 'integer', 'exists:mst_siswa,id'],
            'jawaban_teks' => ['nullable', 'string'],
            'file_siswa' => ['nullable', 'string', 'max:255'],
            'waktu_kumpl' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'mst_tugas_id.required' => 'Tugas wajib dipilih',
            'mst_tugas_id.integer' => 'Tugas tidak valid',
            'mst_tugas_id.exists' => 'Tugas tidak ditemukan',
            'mst_siswa_id.required' => 'Siswa wajib dipilih',
            'mst_siswa_id.integer' => 'Siswa tidak valid',
            'mst_siswa_id.exists' => 'Siswa tidak ditemukan',
            'file_siswa.max' => 'Path file maksimal 255 karakter',
            'waktu_kumpl.date' => 'Format tanggal tidak valid',
        ];
    }
}
