<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgramHeroSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>|string>
     */
    public function rules(): array
    {
        $file = ['nullable', 'file', 'mimes:jpeg,jpg,png,webp', 'max:3072'];

        return [
            'magister_hero' => $file,
            'doktor_hero' => $file,
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'magister_hero.mimes' => 'Gambar Magister harus JPEG, PNG, atau WebP.',
            'magister_hero.max' => 'Gambar Magister maksimal 3 MB.',
            'doktor_hero.mimes' => 'Gambar Doktor harus JPEG, PNG, atau WebP.',
            'doktor_hero.max' => 'Gambar Doktor maksimal 3 MB.',
        ];
    }
}
