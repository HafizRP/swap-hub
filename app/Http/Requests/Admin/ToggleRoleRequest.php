<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ToggleRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role && Auth::user()->role->slug === 'admin';
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'admin_password' => ['required', 'string'],
        ];
    }
}
