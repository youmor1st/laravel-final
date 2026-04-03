<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePointRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_ids' => ['required', 'array', 'min:1'],
            'student_ids.*' => ['integer', 'distinct'],
            'rule_ids' => ['required', 'array', 'min:1'],
            'rule_ids.*' => ['integer', 'distinct'],
            'comment' => ['nullable', 'string', 'max:500'],
        ];
    }
}
