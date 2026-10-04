<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $skillId = $this->route('skill')?->id ?? $this->route('skill');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('skills', 'name')->ignore($skillId)],
            'category' => ['nullable', 'string', 'max:100'],
        ];
    }
}
