<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Guru;

use Illuminate\Foundation\Http\FormRequest;

class CreateGuruRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sys_user_id' => ['nullable', 'integer', 'exists:sys_users,id'],
            'nip' => ['nullable', 'string', 'max:20', 'unique:mst_guru,nip'],
            'nuptk' => ['nullable', 'string', 'max:20'],
            'nama' => ['required', 'string', 'max:100'],
            'jenis_kelamin' => ['required', 'integer', 'min:1', 'max:2'],
            'tanggal_lahir' => ['nullable', 'date', 'before:today'],
            'alamat' => ['nullable', 'string'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'pendidikan_terakhir' => ['nullable', 'integer'],
            'mapel_ids' => ['nullable', 'array'],
            'mapel_ids.*' => ['integer', 'exists:mst_mapel,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'nip.unique' => 'NIP sudah terdaftar',
            'nuptk.unique' => 'NUPTK sudah terdaftar',
            'nama.required' => 'Nama wajib diisi',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'jenis_kelamin.integer' => 'Jenis kelamin harus berupa angka',
            'jenis_kelamin.min' => 'Jenis kelamin tidak valid',
            'jenis_kelamin.max' => 'Jenis kelamin tidak valid',
        ];
    }
}
