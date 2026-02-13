<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\PeminjamanBuku;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePeminjamanBukuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mst_siswa_id' => ['nullable', 'integer', 'exists:mst_siswa,id'],
            'mst_buku_id' => ['nullable', 'integer', 'exists:mst_buku,id'],
            'tanggal_pinjam' => ['nullable', 'date'],
            'tanggal_jatuh_tempo' => ['nullable', 'date'],
            'keterangan' => ['nullable', 'string'],
        ];
    }
}
