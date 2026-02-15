<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Siswa;

use Illuminate\Foundation\Http\FormRequest;

class CreateSiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sys_user_id' => ['required', 'integer', 'exists:sys_users,id'],
            'nis' => ['required', 'string', 'max:20', 'unique:mst_siswa,nis'],
            'nama' => ['required', 'string', 'max:100'],
            'jenis_kelamin' => ['required', 'integer', 'min:1', 'max:2'],
            'tanggal_lahir' => ['nullable', 'date', 'before:today'],
            'alamat' => ['nullable', 'string'],
            'mst_kelas_id' => ['nullable', 'integer', 'exists:mst_kelas,id'],
            'status' => ['nullable', 'integer', 'min:1', 'max:3'],
        ];
    }

    public function messages(): array
    {
        return [
            'sys_user_id.required' => 'User ID wajib diisi',
            'sys_user_id.exists' => 'User tidak ditemukan',
            'nis.required' => 'NIS wajib diisi',
            'nis.unique' => 'NIS sudah terdaftar',
            'nama.required' => 'Nama wajib diisi',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'jenis_kelamin.integer' => 'Jenis kelamin harus berupa angka',
            'jenis_kelamin.min' => 'Jenis kelamin tidak valid',
            'jenis_kelamin.max' => 'Jenis kelamin tidak valid',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini',
            'mst_kelas_id.exists' => 'Kelas tidak ditemukan',
            'status.integer' => 'Status harus berupa angka',
            'status.min' => 'Status tidak valid',
            'status.max' => 'Status tidak valid',
        ];
    }
}
