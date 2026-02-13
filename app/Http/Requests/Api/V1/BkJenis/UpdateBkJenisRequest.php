<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\BkJenis;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBkJenisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'kode' => ['nullable', 'string', 'max:50', 'unique:mst_bk_jenis,kode,' . $id],
            'nama' => ['nullable', 'string', 'max:100'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.unique' => 'Kode sudah terdaftar',
        ];
    }
}
