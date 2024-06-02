<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

use App\Http\Utils\{
    ResponseHandler,
    Authorize
};

class ForgotPassword extends FormRequest
{
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

    public function rules()
    {
        return [
            'email' => 'required|email|max:50',
        ];
    }

    public function messages()
    {
        return [

            'email.required' => 'The Email field is required.',
            'email.email' => 'The Email field shold be Email.',

        ];
    }



    public function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse($validator->errors(), 400)
        );
    }
}
