<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\Password;
use App\Http\Utils\{
    ResponseHandler,
    Authorize
};


class UpdatePassword extends FormRequest
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

            'password' => [
                'required',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],

            'cpassword' => [

                'required', 'same:password',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],


        ];
    }

    public function messages()
    {
        return [

            'password.required' => 'The Password field is required.',
            'cpassword.required' => 'The Confirm Password field is required.',

        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse($validator->errors(), 400)
        );
    }
}
