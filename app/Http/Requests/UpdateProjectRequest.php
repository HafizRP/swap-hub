<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        $project = $this->route('project');

        return auth()->check() && $project && (int) $project->owner_id === (int) auth()->id();
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
            'github_repo_url' => ['nullable', 'url'],
            'github_repo_name' => ['nullable', 'string'],
            'category' => ['required', 'string', 'in:Development,Design,Marketing'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
        ];
    }
}
