<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSkillSwapRequest extends FormRequest
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
            'offered_skill_id' => ['required', 'exists:skills,id'],
            'requested_skill_id' => ['required', 'exists:skills,id', 'different:offered_skill_id'],
            'description' => ['required', 'string', 'min:10', 'max:1000'],
            'points_offered' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
