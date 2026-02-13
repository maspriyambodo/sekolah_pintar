<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\TarifSpp;

use Illuminate\Foundation\Http\FormRequest;

class CreateTarifSppRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mst_kelas_id' => ['required', 'integer', 'exists:mst_kelas,id'],
            'tahun_ajaran' => ['required', 'string', 'max:20', 'regex:/^\d{4}\/\d{4}$/'],
            'nominal' => ['required', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'mst_kelas_id.required' => 'Kelas wajib dipilih',
            'mst_kelas_id.exists' => 'Kelas tidak ditemukan',
            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi',
            'tahun_ajaran.regex' => 'Format tahun ajaran harus YYYY/YYYY (contoh: 2023/2024)',
            'nominal.required' => 'Nominal SPP wajib diisi',
            'nominal.min' => 'Nominal tidak boleh negatif',
        ];
    }
}
