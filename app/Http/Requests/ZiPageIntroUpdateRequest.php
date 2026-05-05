<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ZiPageIntroUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string|ValidationRule>|string>
     */
    public function rules(): array
    {
        return [
            'intro_heading_id' => ['required', 'string', 'max:500'],
            'intro_heading_en' => ['nullable', 'string', 'max:500'],
            'intro_p1_id' => ['required', 'string', 'max:8000'],
            'intro_p1_en' => ['nullable', 'string', 'max:8000'],
            'intro_p2_id' => ['required', 'string', 'max:8000'],
            'intro_p2_en' => ['nullable', 'string', 'max:8000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        foreach (['intro_heading_en', 'intro_p1_en', 'intro_p2_en'] as $key) {
            $v = $this->input($key);
            if ($v !== null && trim((string) $v) === '') {
                $this->merge([$key => null]);
            }
        }
    }
}
