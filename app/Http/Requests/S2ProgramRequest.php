<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class S2ProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        foreach (['name_en', 'blurb_en', 'excerpt_id', 'excerpt_en', 'official_url'] as $key) {
            $v = $this->input($key);
            if ($v !== null && trim((string) $v) === '') {
                $this->merge([$key => null]);
            }
        }
        $slug = $this->input('slug');
        if (is_string($slug) && trim($slug) === '') {
            $this->merge(['slug' => null]);
        }
    }

    /**
     * @return array<string, array<int, string|ValidationRule>|string>
     */
    public function rules(): array
    {
        $program = $this->route('program');

        return [
            'sort_order' => ['required', 'integer', 'min:0', 'max:65535'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('s2_programs', 'slug')->ignore($program?->getKey()),
            ],
            'name_id' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'blurb_id' => ['required', 'string', 'max:5000'],
            'blurb_en' => ['nullable', 'string', 'max:5000'],
            'excerpt_id' => ['nullable', 'string', 'max:2000'],
            'excerpt_en' => ['nullable', 'string', 'max:2000'],
            'official_url' => ['nullable', 'string', 'url', 'max:2048'],
            'brochure_image' => ['nullable', 'image', 'max:5120'],
            'remove_brochure' => ['sometimes', 'boolean'],
        ];
    }
}
