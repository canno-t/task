<?php

namespace App\Http\Requests;

use App\Exceptions\HttpValidationFailException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AssignUsersRequest extends FormRequest
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
            'assigned_users'=>'array|nullable|max:10',
            'assigned_users.*'=>'exists:users,id',
            'uuid'=>'exists:tasks'
        ];
    }

    public function attributes()
    {
        for ($i=0;$i<10;$i++){
            $attributes['assigned_users.'.$i] = 'User'.$i;
        }
        return $attributes;
    }

    public function messages()
    {
        return [
            'assigned_users.*.exists'=>':attribute does not exist'
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
