<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Spk;

use Illuminate\Foundation\Http\FormRequest;

class CreateKriteriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_kriteria' => ['required', 'string', 'max:10', 'unique:spk_kriteria,kode_kriteria'],
            'nama_kriteria' => ['required', 'string', 'max:100'],
            'bobot' => ['required', 'numeric', 'min:0', 'max:100'],
            'tipe' => ['required', 'in:benefit,cost'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_kriteria.required' => 'Kode kriteria wajib diisi',
            'kode_kriteria.max' => 'Kode kriteria maksimal 10 karakter',
            'kode_kriteria.unique' => 'Kode kriteria sudah ada',
            'nama_kriteria.required' => 'Nama kriteria wajib diisi',
            'nama_kriteria.max' => 'Nama kriteria maksimal 100 karakter',
            'bobot.required' => 'Bobot wajib diisi',
            'bobot.numeric' => 'Bobot harus berupa angka',
            'bobot.min' => 'Bobot minimal 0',
            'bobot.max' => 'Bobot maksimal 100',
            'tipe.required' => 'Tipe kriteria wajib dipilih',
            'tipe.in' => 'Tipe kriteria tidak valid',
        ];
    }
}
