<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\PeminjamanBuku;

use Illuminate\Foundation\Http\FormRequest;

class CreatePeminjamanBukuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mst_siswa_id' => ['required', 'integer', 'exists:mst_siswa,id'],
            'mst_buku_id' => ['required', 'integer', 'exists:mst_buku,id'],
            'tanggal_pinjam' => ['nullable', 'date'],
            'tanggal_jatuh_tempo' => ['required', 'date', 'after:tanggal_pinjam'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'mst_siswa_id.required' => 'Siswa wajib dipilih',
            'mst_siswa_id.exists' => 'Siswa tidak ditemukan',
            'mst_buku_id.required' => 'Buku wajib dipilih',
            'mst_buku_id.exists' => 'Buku tidak ditemukan',
            'tanggal_jatuh_tempo.required' => 'Tanggal jatuh tempo wajib diisi',
            'tanggal_jatuh_tempo.after' => 'Tanggal jatuh tempo harus setelah tanggal pinjam',
        ];
    }
}
