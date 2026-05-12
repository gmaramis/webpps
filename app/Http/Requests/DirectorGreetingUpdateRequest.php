<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DirectorGreetingUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if ($user === null) {
            return false;
        }

        return $user->can('director-greeting.manage')
            || $user->can('slideshow.manage')
            || $user->can('program-heroes.manage');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'section_eyebrow_id' => ['required', 'string', 'max:200'],
            'section_eyebrow_en' => ['required', 'string', 'max:200'],
            'section_title_id' => ['required', 'string', 'max:200'],
            'section_title_en' => ['required', 'string', 'max:200'],
            'section_quote_label_id' => ['required', 'string', 'max:200'],
            'section_quote_label_en' => ['required', 'string', 'max:200'],
            'name_id' => ['nullable', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'role_id' => ['nullable', 'string', 'max:255'],
            'role_en' => ['nullable', 'string', 'max:255'],
            'paragraphs' => ['nullable', 'array', 'max:12'],
            'paragraphs.*.id' => ['nullable', 'string', 'max:20000'],
            'paragraphs.*.en' => ['nullable', 'string', 'max:20000'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:3072'],
        ];
    }
}
