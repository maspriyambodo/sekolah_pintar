<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Kelas;

use Illuminate\Foundation\Http\FormRequest;

class CreateKelasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_kelas' => ['required', 'string', 'max:50'],
            'tingkat' => ['required', 'integer', 'min:1', 'max:12'],
            'tahun_ajaran' => ['required', 'string', 'max:20'],
            'wali_guru_id' => ['nullable', 'integer', 'exists:mst_guru,id'],
            'kapasitas' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_kelas.required' => 'Nama kelas wajib diisi',
            'tingkat.required' => 'Tingkat wajib diisi',
            'tingkat.min' => 'Tingkat minimal 1',
            'tingkat.max' => 'Tingkat maksimal 12',
            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi',
            'wali_guru_id.exists' => 'Wali guru tidak ditemukan',
        ];
    }
}
