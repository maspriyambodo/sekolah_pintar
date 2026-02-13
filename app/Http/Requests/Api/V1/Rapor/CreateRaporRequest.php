<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Rapor;

use Illuminate\Foundation\Http\FormRequest;

class CreateRaporRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mst_siswa_id' => ['required', 'integer', 'exists:mst_siswa,id'],
            'mst_kelas_id' => ['required', 'integer', 'exists:mst_kelas,id'],
            'semester' => ['required', 'string', 'max:10'],
            'tahun_ajaran' => ['required', 'string', 'max:20'],
            'catatan_wali' => ['nullable', 'string'],
            'sakit' => ['nullable', 'integer', 'min:0'],
            'izin' => ['nullable', 'integer', 'min:0'],
            'tanpa_keterangan' => ['nullable', 'integer', 'min:0'],
            'details' => ['nullable', 'array'],
            'details.*.mst_mapel_id' => ['required_with:details', 'integer', 'exists:mst_mapel,id'],
            'details.*.nilai_pengetahuan' => ['required_with:details', 'numeric', 'min:0', 'max:100'],
            'details.*.nilai_keterampilan' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'details.*.nilai_akhir' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'details.*.predikat' => ['nullable', 'string', 'max:2'],
            'details.*.deskripsi' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'mst_siswa_id.required' => 'Siswa wajib dipilih',
            'mst_siswa_id.exists' => 'Siswa tidak ditemukan',
            'mst_kelas_id.required' => 'Kelas wajib dipilih',
            'mst_kelas_id.exists' => 'Kelas tidak ditemukan',
        ];
    }
}
