<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Nilai;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNilaiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mst_siswa_id' => ['nullable', 'integer', 'exists:mst_siswa,id'],
            'trx_ujian_id' => ['nullable', 'integer', 'exists:trx_ujian,id'],
            'nilai' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nilai.min' => 'Nilai minimal 0',
            'nilai.max' => 'Nilai maksimal 100',
        ];
    }
}
