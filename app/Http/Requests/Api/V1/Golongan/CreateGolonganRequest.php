<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Golongan;

use Illuminate\Foundation\Http\FormRequest;

class CreateGolonganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pangkat' => ['required', 'string', 'max:50'],
            'golongan_ruang' => ['required', 'string', 'max:5'],
            'jabatan' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'pangkat.required' => 'Pangkat wajib diisi',
            'golongan_ruang.required' => 'Golongan/Ruang wajib diisi',
        ];
    }
}
