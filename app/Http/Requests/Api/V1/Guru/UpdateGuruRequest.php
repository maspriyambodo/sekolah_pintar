<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Guru;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGuruRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'nip' => ['nullable', 'string', 'max:30', 'unique:mst_guru,nip,' . $id],
            'nuptk' => ['nullable', 'string', 'max:30', 'unique:mst_guru,nuptk,' . $id],
            'nama' => ['nullable', 'string', 'max:100'],
            'jenis_kelamin' => ['nullable', 'string', 'in:L,P'],
            'tanggal_lahir' => ['nullable', 'date', 'before:today'],
            'alamat' => ['nullable', 'string'],
            'no_hp' => ['nullable', 'string', 'max:15'],
            'email' => ['nullable', 'email', 'max:100'],
            'pendidikan_terakhir' => ['nullable', 'string', 'max:50'],
            'jabatan' => ['nullable', 'string', 'max:50'],
            'mapel_ids' => ['nullable', 'array'],
            'mapel_ids.*' => ['integer', 'exists:mst_mapel,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'nip.unique' => 'NIP sudah terdaftar',
            'nuptk.unique' => 'NUPTK sudah terdaftar',
            'jenis_kelamin.in' => 'Jenis kelamin harus L atau P',
        ];
    }
}
