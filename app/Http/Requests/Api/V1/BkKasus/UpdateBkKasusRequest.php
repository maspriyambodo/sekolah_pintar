<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\BkKasus;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBkKasusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'siswa_id' => ['nullable', 'integer', 'exists:mst_siswa,id'],
            'guru_id' => ['nullable', 'integer', 'exists:mst_guru,id'],
            'jenis_id' => ['nullable', 'integer', 'exists:mst_bk_jenis,id'],
            'tanggal' => ['nullable', 'date'],
            'keterangan' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'in:open,progress,resolved,closed'],
        ];
    }
}
