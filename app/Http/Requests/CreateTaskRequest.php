<?php

namespace App\Http\Requests;

use App\Exceptions\HttpValidationFailException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CreateTaskRequest extends FormRequest
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
            'name'=>'string|required',
            'description'=>'string|required',
            'finish_date'=>'required|date',
            'tasktype'=>'required|exists:task_types,id',
            'dominant_task_id'=>'required_if:tasktype,==,2',
            'assigned_users'=>'nullable|array',
            'assigned_users.*'=>'exists:users,id'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpValidationFailException($validator->errors()->first());
    }
}
