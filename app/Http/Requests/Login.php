<?php

namespace App\Http\Requests;

use App\Http\Utils\{
    ResponseHandler,
    Authorize
};

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class Login extends FormRequest
{

    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (new Authorize)->checkNotLogin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|regex:/^[A-Za-z0-9@.]+$/|min:10|max:50',
            'password' => 'required|min:6|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'The Username field is required.',
            'username.regex' => 'The Username field is invalid.',
            'password.required' => 'The Password field is required.',
            'password.min' => 'The Password must be at least 8 characters.',
        ];
    }


    public function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse($validator->errors(), 400)
        );
    }

    // Optional Validation

}
