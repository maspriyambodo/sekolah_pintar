<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\BkJenis;

use Illuminate\Foundation\Http\FormRequest;

class CreateBkJenisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode' => ['required', 'string', 'max:50', 'unique:mst_bk_jenis,kode'],
            'nama' => ['required', 'string', 'max:100'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode wajib diisi',
            'kode.unique' => 'Kode sudah terdaftar',
            'nama.required' => 'Nama wajib diisi',
        ];
    }
}
