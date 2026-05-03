<?php

namespace App\Http\Requests;

use App\Models\VisionMissionContent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class VisionMissionUpdateRequest extends FormRequest
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
            'vision_id' => ['required', 'string', 'max:5000'],
            'vision_en' => ['nullable', 'string', 'max:5000'],
            'mission_id' => ['required', 'string', 'max:20000'],
            'mission_en' => ['nullable', 'string', 'max:20000'],
            'values_id' => ['required', 'string', 'max:20000'],
            'values_en' => ['nullable', 'string', 'max:20000'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v): void {
            if (VisionMissionContent::linesFromTextarea((string) $this->input('mission_id', '')) === []) {
                $v->errors()->add('mission_id', 'Minimal satu butir untuk misi bahasa Indonesia.');
            }
            if (VisionMissionContent::linesFromTextarea((string) $this->input('values_id', '')) === []) {
                $v->errors()->add('values_id', 'Minimal satu butir untuk nilai/budaya bahasa Indonesia.');
            }
        });
    }
}
