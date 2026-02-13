<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Kelas;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKelasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_kelas' => ['nullable', 'string', 'max:50'],
            'tingkat' => ['nullable', 'integer', 'min:1', 'max:12'],
            'tahun_ajaran' => ['nullable', 'string', 'max:20'],
            'wali_guru_id' => ['nullable', 'integer', 'exists:mst_guru,id'],
            'kapasitas' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'wali_guru_id.exists' => 'Wali guru tidak ditemukan',
        ];
    }
}
