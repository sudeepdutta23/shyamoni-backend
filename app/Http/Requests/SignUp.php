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

use Illuminate\Http\Request;

use App\Models\User;

class SignUp extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            'name' => 'required|min:5|max:50',
            'email' => 'required|unique:users|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'phoneNo' => 'required|unique:users|regex:/^[6-9]\d{9}$/|min:10|max:10',
            'password' => 'required|min:6|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'cpassword' => 'required|same:password|min:6|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The Name field is required.',
            'email.required' => 'The Email field is required.',
            'email.unique' => 'The Email has already been taken.',
            'phoneNo.required' => 'The Phone No field is required.',
            'phoneNo.unique' => 'The Phone no has already been taken.',
            'password.required' => 'The Password field is required.',
            'passwor.d.min' => 'The Password must be at least 8 characters.',
            'password.regex' => 'The Password Field is invalid.',
            'cpassword.required' => 'The Password field is required.',
            'cpassword.regex' => 'The Confirm Password Field is invalid.',
            'cpassword.min' => 'The Password must be at least 8 characters.',
            'email.regex' => 'The Email format is Invalid',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse($validator->errors(), 400)
        );
    }
}
