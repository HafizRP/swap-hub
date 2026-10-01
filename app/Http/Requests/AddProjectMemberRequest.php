<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AddProjectMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'email' => ['nullable', 'email', 'exists:users,email'],
            'user_id' => ['nullable', 'exists:users,id'],
            'role' => ['nullable', 'string', 'in:member,contributor,owner'],
        ];
    }
}
