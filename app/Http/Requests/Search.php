<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

use App\Http\Utils\{
    ResponseHandler
};

class Search extends FormRequest
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
    public function rules()
    {
        return [
            // 's' =>  'required|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/',
        ];
    }

    public function messages()
    {
        return [

            // 's.required' => 'The Search field is required.',

            // 's.regex' => 'The Search field is invalid.',

        ];
    }


    public function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse($validator->errors(), 400)
        );
    }
}
