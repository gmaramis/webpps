<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StopGratifikasiBulletRequest extends FormRequest
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
            'sort_order' => ['required', 'integer', 'min:0', 'max:999999'],
            'text_id' => ['required', 'string', 'max:20000'],
            'text_en' => ['nullable', 'string', 'max:20000'],
        ];
    }
}
