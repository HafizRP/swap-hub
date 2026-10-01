<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddSkillRequest extends FormRequest
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
            'skill_id' => ['required', 'exists:skills,id'],
            'proficiency_level' => ['required', 'in:beginner,intermediate,advanced,expert'],
        ];
    }
}
