<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Nilai;

use Illuminate\Foundation\Http\FormRequest;

class CreateNilaiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mst_siswa_id' => ['required', 'integer', 'exists:mst_siswa,id'],
            'trx_ujian_id' => ['required', 'integer', 'exists:trx_ujian,id'],
            'nilai' => ['required', 'numeric', 'min:0', 'max:100'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'mst_siswa_id.required' => 'Siswa wajib dipilih',
            'mst_siswa_id.exists' => 'Siswa tidak ditemukan',
            'trx_ujian_id.required' => 'Ujian wajib dipilih',
            'trx_ujian_id.exists' => 'Ujian tidak ditemukan',
            'nilai.required' => 'Nilai wajib diisi',
            'nilai.min' => 'Nilai minimal 0',
            'nilai.max' => 'Nilai maksimal 100',
        ];
    }
}
