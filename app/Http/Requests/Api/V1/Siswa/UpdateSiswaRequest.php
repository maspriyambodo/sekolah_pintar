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
            'jenis_kelamin' => ['nullable', 'string', 'in:L,P'],
            'tanggal_lahir' => ['nullable', 'date', 'before:today'],
            'alamat' => ['nullable', 'string'],
            'mst_kelas_id' => ['nullable', 'integer', 'exists:mst_kelas,id'],
            'status' => ['nullable', 'string', 'in:aktif,lulus,pindah'],
        ];
    }

    public function messages(): array
    {
        return [
            'nis.unique' => 'NIS sudah terdaftar',
            'jenis_kelamin.in' => 'Jenis kelamin harus L atau P',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini',
            'mst_kelas_id.exists' => 'Kelas tidak ditemukan',
            'status.in' => 'Status harus aktif, lulus, atau pindah',
        ];
    }
}
