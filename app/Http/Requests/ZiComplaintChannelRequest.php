<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ZiComplaintChannelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->boolean('is_published'),
            'external' => $this->boolean('external'),
        ]);
        foreach (['title_en', 'summary_en'] as $key) {
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
            'is_published' => ['required', 'boolean'],
            'title_id' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'summary_id' => ['required', 'string', 'max:5000'],
            'summary_en' => ['nullable', 'string', 'max:5000'],
            'href' => ['required', 'string', 'max:2048'],
            'external' => ['required', 'boolean'],
        ];
    }
}
