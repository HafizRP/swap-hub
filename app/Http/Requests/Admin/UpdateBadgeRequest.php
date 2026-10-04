<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBadgeRequest extends FormRequest
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
        $badgeId = $this->route('badge')?->id ?? $this->route('badge');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('badges', 'name')->ignore($badgeId)],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'points_required' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
