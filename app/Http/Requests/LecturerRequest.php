<?php

namespace App\Http\Requests;

use App\Models\Lecturer;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LecturerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        foreach (['name_en', 'nidn', 'nip', 'phone', 'email', 'google_scholar_url'] as $key) {
            $v = $this->input($key);
            if ($v !== null && trim((string) $v) === '') {
                $this->merge([$key => null]);
            }
        }

        $rawSp = $this->input('study_program_id');
        if (is_string($rawSp)) {
            $spId = trim($rawSp);
            $en = Lecturer::resolveStudyProgramEnglishFromId($spId);
            if ($en !== null) {
                $this->merge([
                    'study_program_id' => $spId,
                    'study_program_en' => $en,
                ]);
            }
        }

        $rawRank = $this->input('functional_role_id');
        if (is_string($rawRank)) {
            $rankId = trim($rawRank);
            if (in_array($rankId, Lecturer::functionalRankIds(), true)) {
                $this->merge([
                    'functional_role_id' => $rankId,
                    'functional_role_en' => Lecturer::functionalRankEnglish($rankId),
                ]);
            }
        }
    }

    /**
     * @return array<string, array<int, string|ValidationRule>|string>
     */
    public function rules(): array
    {
        $allowedPrograms = Lecturer::studyProgramNameIdsFromDatabase();
        $studyProgramIdRules = ['required', 'string', 'max:255'];
        if ($allowedPrograms !== []) {
            $studyProgramIdRules[] = Rule::in($allowedPrograms);
        } else {
            $studyProgramIdRules[] = static function (string $attribute, mixed $value, \Closure $fail): void {
                $fail('Tambahkan program studi (Prodi Magister atau Doktor) di basis data terlebih dahulu.');
            };
        }

        return [
            'sort_order' => ['required', 'integer', 'min:0', 'max:65535'],
            'name_id' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'nidn' => ['nullable', 'string', 'max:32'],
            'nip' => ['nullable', 'string', 'max:128'],
            'study_program_id' => $studyProgramIdRules,
            'study_program_en' => ['nullable', 'string', 'max:255'],
            'functional_role_id' => ['required', 'string', Rule::in(Lecturer::functionalRankIds())],
            'functional_role_en' => ['nullable', 'string', 'max:191'],
            'phone' => ['nullable', 'string', 'max:64'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255'],
            'google_scholar_url' => ['nullable', 'string', 'url', 'max:512'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
