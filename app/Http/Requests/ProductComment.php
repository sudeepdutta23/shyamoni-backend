<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Utils\{
    ResponseHandler
};

class ProductComment extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function authorize()
    {
        // To do Check weather the user is logged in or not
        return true;
    }


    public function rules()
    {
        return [
            'userRating' => 'required|numeric|min:1|max:10',
            'comment' => 'required|regex:/^[a-zA-Z0-9 ]+$/',
        ];
    }

    public function messages()
    {
        return [

            'userRating.required' => 'The User Rating field is required.',
            'userRating.numeric' => 'The User Rating field is invalid.',

            'comment.required' => 'The Comment field is required.',
            'comment.regex' => 'The Comment field is invalid.',
        ];
    }

    public function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse($validator->errors(), 400)
        );
    }
}
