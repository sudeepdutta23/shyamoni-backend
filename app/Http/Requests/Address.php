<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Utils\{
    ResponseHandler,
    Authorize
};

use Illuminate\Support\Facades\Auth;

class Address extends FormRequest
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
        return (new Authorize)->checkUser();
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'address_line_1' =>  'required|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/',
            'address_line_1' =>  'required|regex:/(^[-0-9A-Za-z.,\/ ]+$)/',

            // 'address_line_2' =>  'required|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/',
            'address_line_2' =>  'required|regex:/(^[-0-9A-Za-z.,\/ ]+$)/',


            // required|regex:/(^[-0-9A-Za-z.,\/ ]+$)/
            // 'city' =>  'required|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/',
            'cityID' =>  'required|numeric',

            // 'district' =>  'required|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/',
            'district' =>  'required',

            // 'state' =>  'required|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/',
            'stateID' =>  'required|numeric',
            'country' =>  'required|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/',
            'zip' => 'required|digits:6|numeric',
        ];
    }


    public function messages()
    {
        return [

            'address_line_1.required' => 'The Address Line 1 field is required.',
            'address_line_2.required' => 'The Address Line 2 field is required.',
            'city.required' => 'The City field is required.',
            'district.required' => 'The District field is required.',
            'state.required' => 'The State field is required.',
            'country.required' => 'The Country field is required.',
            'zip.required' => 'The Zip field is required.',

            'address_line_1.regex' => 'The Address Line 1 field is invalid.',
            'address_line_2.regex' => 'The Address Line 2 field is invalid.',
            'city.regex' => 'The City field is invalid.',
            'district.regex' => 'The District field is invalid.',
            'state.regex' => 'The State field is invalid.',
            'country.regex' => 'The Country field is invalid.',
            'zip.numeric' => 'The Zip field is invalid.',
        ];
    }



    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse($validator->errors(), 400)
        );
    }
}
