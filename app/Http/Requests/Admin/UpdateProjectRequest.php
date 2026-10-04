<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProjectRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'status' => ['required', 'in:planning,active,completed,archived'],
            'category' => ['nullable', 'string', 'max:100'],
            'max_members' => ['nullable', 'integer', 'min:1', 'max:50'],
            'github_repo_url' => ['nullable', 'url', 'max:255'],
        ];
    }
}
