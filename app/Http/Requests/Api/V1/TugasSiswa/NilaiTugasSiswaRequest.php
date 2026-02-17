<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\TugasSiswa;

use Illuminate\Foundation\Http\FormRequest;

class NilaiTugasSiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nilai' => ['required', 'numeric', 'min:0', 'max:100'],
            'catatan_guru' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nilai.required' => 'Nilai wajib diisi',
            'nilai.numeric' => 'Nilai harus berupa angka',
            'nilai.min' => 'Nilai minimal 0',
            'nilai.max' => 'Nilai maksimal 100',
        ];
    }
}
