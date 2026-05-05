<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccreditationDocumentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string|string>|string>
     */
    public function rules(): array
    {
        return [
            'sort_order' => ['required', 'integer', 'min:0', 'max:999999'],
            'title_id' => ['required', 'string', 'max:500'],
            'title_en' => ['nullable', 'string', 'max:500'],
            'is_published' => ['sometimes', 'boolean'],
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->boolean('is_published'),
        ]);
    }
}
