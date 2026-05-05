<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StopGratifikasiSimplePageUpdateRequest extends FormRequest
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
            'title_id' => ['required', 'string', 'max:500'],
            'simple_body_id' => ['required', 'string', 'max:100000'],
            'link_instrumen_zi_url' => ['nullable', 'string', 'max:2048'],
            'title_en' => ['nullable', 'string', 'max:500'],
            'simple_body_en' => ['nullable', 'string', 'max:100000'],
        ];
    }
}
