<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Buku;

use Illuminate\Foundation\Http\FormRequest;

class CreateBukuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'isbn' => ['nullable', 'string', 'max:20', 'unique:mst_buku,isbn'],
            'judul' => ['required', 'string', 'max:200'],
            'penulis' => ['nullable', 'string', 'max:100'],
            'penerbit' => ['nullable', 'string', 'max:100'],
            'tahun_terbit' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'jumlah_halaman' => ['nullable', 'integer', 'min:1'],
            'stok' => ['nullable', 'integer', 'min:0'],
            'kategori_id' => ['nullable', 'integer', 'exists:mst_bk_kategori,id'],
            'deskripsi' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'isbn.unique' => 'ISBN sudah terdaftar',
            'judul.required' => 'Judul buku wajib diisi',
            'tahun_terbit.min' => 'Tahun terbit tidak valid',
            'tahun_terbit.max' => 'Tahun terbit tidak valid',
            'kategori_id.exists' => 'Kategori tidak ditemukan',
        ];
    }
}
