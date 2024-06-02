<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Utils\{
    ResponseHandler,
    Authorize
};

class OTPVerify extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'userOTP'=>'required|digits:4|numeric',

        ];
    }

    public function messages()
    {
        return[
            'userOTP.required' => 'The OTP field is required.',
            'userOTP.numeric' => 'The OTP field is invalid.',
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse($validator->errors(), 400)
        );
    }
}
