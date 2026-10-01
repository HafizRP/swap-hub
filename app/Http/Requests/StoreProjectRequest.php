<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'github_repo_url' => ['nullable', 'url'],
            'github_repo_name' => ['nullable', 'string'],
            'category' => ['required', 'string', 'in:Development,Design,Marketing'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'setup_webhook' => ['nullable', 'boolean'],
        ];
    }
}
