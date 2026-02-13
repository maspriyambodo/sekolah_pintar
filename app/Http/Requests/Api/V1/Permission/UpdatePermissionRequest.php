<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Permission;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'name' => ['nullable', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:sys_permissions,slug,' . $id],
            'module' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.unique' => 'Slug sudah terdaftar',
        ];
    }
}
