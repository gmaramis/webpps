<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GraduateSchoolHistoryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'eyebrow_id' => ['required', 'string', 'max:255'],
            'eyebrow_en' => ['nullable', 'string', 'max:255'],
            'title_id' => ['required', 'string', 'max:500'],
            'title_en' => ['nullable', 'string', 'max:500'],
            'paragraph_id' => ['required', 'string', 'max:20000'],
            'paragraph_en' => ['nullable', 'string', 'max:20000'],
            'image' => [
                'nullable',
                'image',
                'max:1536',
                Rule::dimensions()->maxWidth(1200)->maxHeight(1600),
            ],
            'remove_image' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'image.max' => 'Ukuran berkas gambar maksimal 1,5 MB.',
            'image.dimensions' => 'Gambar terlalu besar: lebar maksimal 1200 px, tinggi maksimal 1600 px.',
        ];
    }
}
