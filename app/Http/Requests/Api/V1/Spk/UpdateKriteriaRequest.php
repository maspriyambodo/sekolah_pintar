<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Spk;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKriteriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_kriteria' => ['sometimes', 'string', 'max:10', 'unique:spk_kriteria,kode_kriteria,' . $this->route('id')],
            'nama_kriteria' => ['sometimes', 'string', 'max:100'],
            'bobot' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'tipe' => ['sometimes', 'in:benefit,cost'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_kriteria.max' => 'Kode kriteria maksimal 10 karakter',
            'kode_kriteria.unique' => 'Kode kriteria sudah ada',
            'nama_kriteria.max' => 'Nama kriteria maksimal 100 karakter',
            'bobot.numeric' => 'Bobot harus berupa angka',
            'bobot.min' => 'Bobot minimal 0',
            'bobot.max' => 'Bobot maksimal 100',
            'tipe.in' => 'Tipe kriteria tidak valid',
        ];
    }
}
