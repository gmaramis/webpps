<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ZiGalleryItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->boolean('is_published'),
        ]);
        foreach (['image_alt_en', 'caption_en'] as $key) {
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
        $photo = $this->route('galleryItem') !== null
            ? ['nullable', 'file', 'mimes:jpeg,jpg,png,webp,gif,svg', 'max:4096']
            : ['required', 'file', 'mimes:jpeg,jpg,png,webp,gif,svg', 'max:4096'];

        return [
            'sort_order' => ['required', 'integer', 'min:0', 'max:65535'],
            'is_published' => ['required', 'boolean'],
            'image_alt_id' => ['required', 'string', 'max:255'],
            'image_alt_en' => ['nullable', 'string', 'max:255'],
            'caption_id' => ['required', 'string', 'max:500'],
            'caption_en' => ['nullable', 'string', 'max:500'],
            'photo' => $photo,
        ];
    }
}
