<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\Password;
use App\Http\Utils\{
    ResponseHandler,
    Authorize,
};

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use App\Models\User;

class updateProfile extends FormRequest
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
        return true;

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {

        return [

            'email' => 'unique:users,email,'.$this->user_id.',id|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'phoneNo' => 'unique:users,phoneNo,'.$this->user_id.',id|regex:/^[6-9]\d{9}$/|min:10|max:10',


        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'The Email has already been taken.',
            'phoneNo.unique' => 'The Phone no has already been taken.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse($validator->errors(), 400)
        );
    }
}
