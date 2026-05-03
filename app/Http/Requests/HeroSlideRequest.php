<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HeroSlideRequest extends FormRequest
{
    private const REQUIRED_WIDTH = 1600;

    private const REQUIRED_HEIGHT = 700;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>|string>
     */
    public function rules(): array
    {
        $imageRules = $this->isMethod('POST')
            ? ['required', 'file', 'mimes:jpeg,jpg', 'max:500', 'dimensions:width='.self::REQUIRED_WIDTH.',height='.self::REQUIRED_HEIGHT]
            : ['nullable', 'file', 'mimes:jpeg,jpg', 'max:500', 'dimensions:width='.self::REQUIRED_WIDTH.',height='.self::REQUIRED_HEIGHT];

        return [
            'sort_order' => ['required', 'integer', 'min:0', 'max:65535'],
            'image' => $imageRules,
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'image.required' => 'Gambar slide wajib diunggah.',
            'image.mimes' => 'Format gambar harus JPEG (.jpg atau .jpeg).',
            'image.max' => 'Ukuran gambar maksimal 500KB.',
            'image.dimensions' => 'Ukuran gambar harus tepat 1600 x 700 piksel.',
        ];
    }
}
