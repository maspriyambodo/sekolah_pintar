<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\BkKasus;

use Illuminate\Foundation\Http\FormRequest;

class CreateBkKasusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'siswa_id' => ['required', 'integer', 'exists:mst_siswa,id'],
            'guru_id' => ['required', 'integer', 'exists:mst_guru,id'],
            'jenis_id' => ['required', 'integer', 'exists:mst_bk_jenis,id'],
            'tanggal' => ['required', 'date'],
            'keterangan' => ['required', 'string'],
            'status' => ['nullable', 'integer', 'min:1', 'max:4'],
        ];
    }

    public function messages(): array
    {
        return [
            'siswa_id.required' => 'Siswa wajib dipilih',
            'siswa_id.exists' => 'Siswa tidak ditemukan',
            'guru_id.required' => 'Guru wajib dipilih',
            'guru_id.exists' => 'Guru tidak ditemukan',
            'jenis_id.required' => 'Jenis kasus wajib dipilih',
            'jenis_id.exists' => 'Jenis kasus tidak ditemukan',
            'tanggal.required' => 'Tanggal wajib diisi',
            'keterangan.required' => 'Keterangan wajib diisi',
            'status.integer' => 'Status harus berupa angka',
            'status.min' => 'Status tidak valid',
            'status.max' => 'Status tidak valid',
        ];
    }
}
