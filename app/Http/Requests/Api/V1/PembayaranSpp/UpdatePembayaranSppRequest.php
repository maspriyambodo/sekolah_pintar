<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\PembayaranSpp;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePembayaranSppRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal_bayar' => ['nullable', 'date'],
            'jumlah_bayar' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'integer', 'min:1', 'max:4'],
            'metode_pembayaran' => ['nullable', 'integer', 'min:1', 'max:4'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'jumlah_bayar.min' => 'Jumlah bayar tidak boleh negatif',
            'status.integer' => 'Status harus berupa angka',
            'status.min' => 'Status tidak valid',
            'status.max' => 'Status tidak valid',
            'metode_pembayaran.integer' => 'Metode pembayaran harus berupa angka',
            'metode_pembayaran.min' => 'Metode pembayaran tidak valid',
            'metode_pembayaran.max' => 'Metode pembayaran tidak valid',
        ];
    }
}
