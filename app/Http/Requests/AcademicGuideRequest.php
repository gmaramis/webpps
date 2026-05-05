<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AcademicGuideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $v = $this->input('name_en');
        if ($v !== null && trim((string) $v) === '') {
            $this->merge(['name_en' => null]);
        }
        $this->merge([
            'show_on_academic_calendar' => $this->boolean('show_on_academic_calendar'),
        ]);
    }

    /**
     * @return array<string, array<int, string|ValidationRule>|string>
     */
    public function rules(): array
    {
        $pdf = $this->route('guide') !== null
            ? ['nullable', 'file', 'mimes:pdf', 'max:20480']
            : ['required', 'file', 'mimes:pdf', 'max:20480'];

        return [
            'sort_order' => ['required', 'integer', 'min:0', 'max:65535'],
            'name_id' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'show_on_academic_calendar' => ['boolean'],
            'pdf' => $pdf,
        ];
    }
}
