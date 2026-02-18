<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Sekolah;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSekolahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $sekolahId = $this->route('id');

        return [
            'uuid' => ['sometimes', 'string', 'max:36', 'unique:mst_sekolah,uuid,' . $sekolahId],
            'npsn' => ['nullable', 'string', 'max:20'],
            'nama_sekolah' => ['sometimes', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'logo_path' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'subscription_plan' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_sekolah.required' => 'Nama sekolah wajib diisi',
            'uuid.unique' => 'UUID sudah digunakan',
        ];
    }
}
