<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Forum;

use Illuminate\Foundation\Http\FormRequest;

class UpdateForumRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul' => ['sometimes', 'string', 'max:255'],
            'pesan' => ['sometimes', 'string'],
            'file_lampiran' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'judul.max' => 'Judul maksimal 255 karakter',
            'file_lampiran.max' => 'Path file maksimal 255 karakter',
        ];
    }
}
