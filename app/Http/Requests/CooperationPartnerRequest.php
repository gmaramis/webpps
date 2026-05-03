<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CooperationPartnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        foreach (['name_en', 'cooperation_en'] as $key) {
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
            'cooperation_id' => ['required', 'string', 'max:500'],
            'cooperation_en' => ['nullable', 'string', 'max:500'],
            'logo' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
