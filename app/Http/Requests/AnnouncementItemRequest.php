<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $titleEn = $this->input('title_en');
        if ($titleEn !== null && trim((string) $titleEn) === '') {
            $this->merge(['title_en' => null]);
        }

        $href = $this->input('href');
        if ($href !== null && trim((string) $href) === '') {
            $this->merge(['href' => '#']);
        }
    }

    /**
     * @return array<string, array<int, mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sort_order' => ['required', 'integer', 'min:0', 'max:65535'],
            'title_id' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'date_iso' => ['required', 'date'],
            'href' => ['required', 'string', 'max:500'],
        ];
    }
}
