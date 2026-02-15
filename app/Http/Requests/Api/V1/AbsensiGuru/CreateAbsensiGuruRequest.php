<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\AbsensiGuru;

use Illuminate\Foundation\Http\FormRequest;

class CreateAbsensiGuruRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'guru_id' => ['required', 'integer', 'exists:mst_guru,id'],
            'tanggal' => ['required', 'date'],
            'status' => ['required', 'integer', 'min:1', 'max:4'],
            'keterangan' => ['nullable', 'string'],
            'jam_masuk' => ['nullable', 'date_format:H:i'],
            'jam_keluar' => ['nullable', 'date_format:H:i'],
        ];
    }

    public function messages(): array
    {
        return [
            'guru_id.required' => 'Guru ID wajib diisi',
            'guru_id.exists' => 'Guru tidak ditemukan',
            'tanggal.required' => 'Tanggal wajib diisi',
            'tanggal.date' => 'Tanggal tidak valid',
            'status.required' => 'Status wajib diisi',
            'status.integer' => 'Status harus berupa angka',
            'status.min' => 'Status tidak valid',
            'status.max' => 'Status tidak valid',
            'jam_masuk.date_format' => 'Format jam masuk tidak valid (HH:mm)',
            'jam_keluar.date_format' => 'Format jam keluar tidak valid (HH:mm)',
        ];
    }
}
