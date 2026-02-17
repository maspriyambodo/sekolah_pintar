<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Materi;

use Illuminate\Foundation\Http\FormRequest;

class CreateMateriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mst_guru_mapel_id' => ['required', 'integer', 'exists:mst_guru_mapel,id'],
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'file_materi' => ['nullable', 'string', 'max:255'],
            'link_video' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'integer', 'min:0', 'max:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'mst_guru_mapel_id.required' => 'Guru mapel wajib dipilih',
            'mst_guru_mapel_id.integer' => 'Guru mapel tidak valid',
            'mst_guru_mapel_id.exists' => 'Guru mapel tidak ditemukan',
            'judul.required' => 'Judul wajib diisi',
            'judul.string' => 'Judul harus berupa teks',
            'judul.max' => 'Judul maksimal 255 karakter',
            'file_materi.max' => 'Path file maksimal 255 karakter',
            'link_video.max' => 'Link video maksimal 255 karakter',
            'status.integer' => 'Status tidak valid',
            'status.min' => 'Status tidak valid',
            'status.max' => 'Status tidak valid',
        ];
    }
}
