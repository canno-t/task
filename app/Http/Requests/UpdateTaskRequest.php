<?php

namespace App\Http\Requests;

use App\Exceptions\HttpValidationFailException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'finish_date'=>'nullable|date',
            'description'=>'nullable|string',
            'name'=>'nullable|string',
            'uuid'=>'exists:tasks'
        ];
    }

    public function validateResolved()
    {
        $this->merge([
            'uuid' => $this->route('uuid'),
        ]);
        parent::validateResolved();
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpValidationFailException($validator->errors()->first());
    }
}
