<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Siswa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $siswaId = $this->route('id');

        return [
            'nis' => ['nullable', 'string', 'max:20', Rule::unique('mst_siswa', 'nis')->ignore($siswaId)],
            'nama' => ['nullable', 'string', 'max:100'],
            'jenis_kelamin' => ['nullable', 'integer', 'min:1', 'max:2'],
            'tanggal_lahir' => ['nullable', 'date', 'before:today'],
            'alamat' => ['nullable', 'string'],
            'mst_kelas_id' => ['nullable', 'integer', 'exists:mst_kelas,id'],
            'status' => ['nullable', 'integer', 'min:1', 'max:3'],
        ];
    }

    public function messages(): array
    {
        return [
            'nis.unique' => 'NIS sudah terdaftar',
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
