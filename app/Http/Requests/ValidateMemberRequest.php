<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateMemberRequest extends FormRequest
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
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
