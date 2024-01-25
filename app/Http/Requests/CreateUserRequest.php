<?php

namespace App\Http\Requests;

use App\Exceptions\HttpValidationFailException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'name'=>'required|string',
            'email'=>'required|email',
            'password'=>'required|string'//TODO implement some security constrains
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpValidationFailException($validator->errors()->first());
    }
}
