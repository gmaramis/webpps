<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LecturerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        foreach (['name_en', 'study_program_en', 'functional_role_en', 'nidn', 'nip', 'phone'] as $key) {
            $v = $this->input($key);
            if ($v !== null && trim((string) $v) === '') {
                $this->merge([$key => null]);
            }
        }
    }

    /**
     * @return array<string, array<int, string|ValidationRule>|string>
     */
    public function rules(): array
    {
        return [
            'sort_order' => ['required', 'integer', 'min:0', 'max:65535'],
            'name_id' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'nidn' => ['nullable', 'string', 'max:32'],
            'nip' => ['nullable', 'string', 'max:128'],
            'study_program_id' => ['required', 'string', 'max:255'],
            'study_program_en' => ['nullable', 'string', 'max:255'],
            'functional_role_id' => ['required', 'string', 'max:191'],
            'functional_role_en' => ['nullable', 'string', 'max:191'],
            'phone' => ['nullable', 'string', 'max:64'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
