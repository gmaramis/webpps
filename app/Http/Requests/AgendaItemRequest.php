<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AgendaItemRequest extends FormRequest
{
    private const MONTH_ID_VALUES = ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGS', 'SEP', 'OKT', 'NOV', 'DES'];

    private const MONTH_EN_VALUES = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->boolean('is_published'),
        ]);

        foreach (['month_en', 'title_en'] as $key) {
            $value = $this->input($key);
            if ($value !== null && trim((string) $value) === '') {
                $this->merge([$key => null]);
            }
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
            'is_published' => ['required', 'boolean'],
            'day' => ['required', 'regex:/^(0[1-9]|[12][0-9]|3[01])$/'],
            'month_id' => ['required', Rule::in(self::MONTH_ID_VALUES)],
            'month_en' => ['nullable', Rule::in(self::MONTH_EN_VALUES)],
            'title_id' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'href' => ['required', 'string', 'max:500'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'day.regex' => 'Hari harus dalam format 2 digit antara 01 sampai 31.',
            'month_id.in' => 'Bulan (ID) harus dipilih dari daftar.',
            'month_en.in' => 'Bulan (EN) harus dipilih dari daftar.',
        ];
    }
}
