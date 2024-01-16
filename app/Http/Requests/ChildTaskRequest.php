<?php

namespace App\Http\Requests;

use App\Exceptions\HttpValidationFailException;
use http\Env\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ChildTaskRequest extends FormRequest
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
            'author_id'=>'required|exists:users,id',//nie może wypirtdalać 404
            //task type id is added in task creating proccess
            'finish_date'=>'required|date',
            'task_priority_id'=>'required|exists:task_priorities,id',
            'dominant_type_id'=>'nullable|exists:tasks,id'
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpValidationFailException($validator->errors()->first());
    }
}
