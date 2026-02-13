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
            'status' => ['nullable', 'string', 'in:lunas,belum_lunas,pending,dibatalkan'],
            'metode_pembayaran' => ['nullable', 'string', 'in:tunai,transfer,virtual_account,qris'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'jumlah_bayar.min' => 'Jumlah bayar tidak boleh negatif',
        ];
    }
}
