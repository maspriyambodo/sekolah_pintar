<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Mapel;

use Illuminate\Foundation\Http\FormRequest;

class CreateMapelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode' => ['required', 'string', 'max:20', 'unique:mst_mapel,kode_mapel'],
            'nama' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode mapel wajib diisi',
            'kode.unique' => 'Kode mapel sudah terdaftar',
            'nama.required' => 'Nama mapel wajib diisi',
        ];
    }
}
