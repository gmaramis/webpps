<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LeadershipPersonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $nip = $this->input('nip');
        if ($nip !== null && trim((string) $nip) === '') {
            $this->merge(['nip' => null]);
        }
        $roleEn = $this->input('role_en');
        if ($roleEn !== null && trim((string) $roleEn) === '') {
            $this->merge(['role_en' => null]);
        }
    }

    /**
     * @return array<string, array<int, string|ValidationRule>|string>
     */
    public function rules(): array
    {
        return [
            'sort_order' => ['required', 'integer', 'min:0', 'max:65535'],
            'name' => ['required', 'string', 'max:191'],
            'nip' => ['nullable', 'string', 'max:128'],
            'role_id' => ['required', 'string', 'max:191'],
            'role_en' => ['nullable', 'string', 'max:191'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
