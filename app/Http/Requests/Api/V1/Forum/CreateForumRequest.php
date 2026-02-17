<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Forum;

use Illuminate\Foundation\Http\FormRequest;

class CreateForumRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mst_guru_mapel_id' => ['required', 'integer', 'exists:mst_guru_mapel,id'],
            'sys_user_id' => ['required', 'integer', 'exists:sys_users,id'],
            'parent_id' => ['nullable', 'integer', 'exists:trx_forum,id'],
            'judul' => ['nullable', 'string', 'max:255'],
            'pesan' => ['required', 'string'],
            'file_lampiran' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'mst_guru_mapel_id.required' => 'Guru mapel wajib dipilih',
            'mst_guru_mapel_id.integer' => 'Guru mapel tidak valid',
            'mst_guru_mapel_id.exists' => 'Guru mapel tidak ditemukan',
            'sys_user_id.required' => 'User wajib dipilih',
            'sys_user_id.integer' => 'User tidak valid',
            'sys_user_id.exists' => 'User tidak ditemukan',
            'parent_id.integer' => 'Parent tidak valid',
            'parent_id.exists' => 'Parent tidak ditemukan',
            'judul.max' => 'Judul maksimal 255 karakter',
            'pesan.required' => 'Pesan wajib diisi',
            'file_lampiran.max' => 'Path file maksimal 255 karakter',
        ];
    }
}
