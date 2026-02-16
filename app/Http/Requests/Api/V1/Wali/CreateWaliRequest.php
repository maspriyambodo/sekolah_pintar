<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Wali;

use Illuminate\Foundation\Http\FormRequest;

class CreateWaliRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sys_user_id' => ['required', 'integer', 'exists:sys_users,id'],
            'nama' => ['required', 'string', 'max:100'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'sys_user_id.required' => 'User ID wajib diisi',
            'sys_user_id.exists' => 'User tidak ditemukan',
            'nama.required' => 'Nama wajib diisi',
            'no_hp.max' => 'Nomor HP maksimal 20 karakter',
        ];
    }
}
