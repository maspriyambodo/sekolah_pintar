<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\PembayaranSpp;

use Illuminate\Foundation\Http\FormRequest;

class CreatePembayaranSppRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mst_siswa_id' => ['required', 'integer', 'exists:mst_siswa,id'],
            'mst_tarif_spp_id' => ['required', 'integer', 'exists:mst_tarif_spp,id'],
            'bulan' => ['required', 'integer', 'min:1', 'max:12'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
            'tanggal_bayar' => ['nullable', 'date'],
            'jumlah_bayar' => ['required', 'numeric', 'min:0'],
            'status' => ['nullable', 'string', 'in:lunas,belum_lunas,pending,dibatalkan'],
            'metode_pembayaran' => ['nullable', 'string', 'in:tunai,transfer,virtual_account,qris'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'mst_siswa_id.required' => 'Siswa wajib dipilih',
            'mst_siswa_id.exists' => 'Siswa tidak ditemukan',
            'mst_tarif_spp_id.required' => 'Tarif SPP wajib dipilih',
            'mst_tarif_spp_id.exists' => 'Tarif SPP tidak ditemukan',
            'bulan.required' => 'Bulan wajib diisi',
            'bulan.min' => 'Bulan harus antara 1-12',
            'bulan.max' => 'Bulan harus antara 1-12',
            'tahun.required' => 'Tahun wajib diisi',
            'jumlah_bayar.required' => 'Jumlah bayar wajib diisi',
            'jumlah_bayar.min' => 'Jumlah bayar tidak boleh negatif',
        ];
    }
}
