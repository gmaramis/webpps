<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AcademicPortalSettingUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        foreach (['portal_url', 'lms_url', 'spada_url'] as $key) {
            $v = $this->input($key);
            if (is_string($v)) {
                $this->merge([$key => trim($v)]);
            }
        }
    }

    /**
     * @return array<string, array<int, string|ValidationRule>|string>
     */
    public function rules(): array
    {
        return [
            'portal_url' => ['required', 'string', 'url', 'max:2048'],
            'lms_url' => ['required', 'string', 'url', 'max:2048'],
            'spada_url' => ['required', 'string', 'url', 'max:2048'],
        ];
    }
}
