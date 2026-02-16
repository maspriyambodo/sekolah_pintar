<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Mapel;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMapelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'kode' => ['nullable', 'string', 'max:20', 'unique:mst_mapel,kode_mapel,' . $id],
            'nama' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.unique' => 'Kode mapel sudah terdaftar',
        ];
    }
}
