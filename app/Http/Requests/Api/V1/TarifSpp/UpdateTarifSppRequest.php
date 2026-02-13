<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\TarifSpp;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTarifSppRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mst_kelas_id' => ['nullable', 'integer', 'exists:mst_kelas,id'],
            'tahun_ajaran' => ['nullable', 'string', 'max:20', 'regex:/^\d{4}\/\d{4}$/'],
            'nominal' => ['nullable', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'tahun_ajaran.regex' => 'Format tahun ajaran harus YYYY/YYYY (contoh: 2023/2024)',
            'nominal.min' => 'Nominal tidak boleh negatif',
        ];
    }
}
