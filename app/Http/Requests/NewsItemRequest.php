<?php

namespace App\Http\Requests;

use App\Models\NewsItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class NewsItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>|string>
     */
    public function rules(): array
    {
        return [
            'is_published' => ['required', 'boolean'],
            'author' => ['nullable', 'string', 'max:191'],
            'title' => ['required', 'array'],
            'title.id' => ['required', 'string', 'max:255'],
            'title.en' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['required', 'array'],
            'excerpt.id' => ['required', 'string', 'max:5000'],
            'excerpt.en' => ['nullable', 'string', 'max:5000'],
            'body' => ['required', 'array'],
            'body.id' => ['required', 'string', 'max:100000'],
            'body.en' => ['nullable', 'string', 'max:100000'],
            'meta_title' => ['nullable', 'array'],
            'meta_title.id' => ['nullable', 'string', 'max:255'],
            'meta_title.en' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'array'],
            'meta_description.id' => ['nullable', 'string', 'max:500'],
            'meta_description.en' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:300'],
            'remove_image' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'image.max' => 'Gambar tidak boleh lebih besar dari 300 KB.',
            'image.mimes' => 'Gunakan JPG, PNG, GIF, atau WebP.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $v = $this->input('is_published');
        $published = in_array((string) $v, ['1', 'true', 'on', 'yes'], true) || $v === true || $v === 1;

        $patch = ['is_published' => $published];
        $existing = $this->route('news');
        $existing = $existing instanceof NewsItem ? $existing : null;

        foreach (['title', 'excerpt', 'body', 'meta_title', 'meta_description'] as $field) {
            $arr = $this->input($field);
            if (! is_array($arr)) {
                continue;
            }
            if (! array_key_exists('en', $arr) || $arr['en'] === null) {
                $arr['en'] = $existing !== null
                    ? (string) $existing->getTranslationWithoutFallback($field, 'en')
                    : '';
            }
            $patch[$field] = $arr;
        }

        $this->merge($patch);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v): void {
            if (! $this->boolean('is_published')) {
                return;
            }
            foreach (['title.en' => 'Judul (English)', 'excerpt.en' => 'Ringkasan (English)', 'body.en' => 'Isi lengkap (English)'] as $key => $label) {
                $val = $this->input($key);
                if ($val === null || trim((string) $val) === '') {
                    $v->errors()->add($key, $label.' wajib diisi sebelum dipublikasikan (periksa hasil terjemahan).');
                }
            }
        });
    }
}
