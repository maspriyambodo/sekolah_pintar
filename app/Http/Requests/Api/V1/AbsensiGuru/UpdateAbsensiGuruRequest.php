<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\AbsensiGuru;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAbsensiGuruRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'guru_id' => ['nullable', 'integer', 'exists:mst_guru,id'],
            'tanggal' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'in:hadir,izin,sakit,alpha'],
            'keterangan' => ['nullable', 'string'],
            'jam_masuk' => ['nullable', 'date_format:H:i'],
            'jam_keluar' => ['nullable', 'date_format:H:i'],
        ];
    }

    public function messages(): array
    {
        return [
            'guru_id.exists' => 'Guru tidak ditemukan',
            'tanggal.date' => 'Tanggal tidak valid',
            'status.in' => 'Status harus hadir, izin, sakit, atau alpha',
            'jam_masuk.date_format' => 'Format jam masuk tidak valid (HH:mm)',
            'jam_keluar.date_format' => 'Format jam keluar tidak valid (HH:mm)',
        ];
    }
}
