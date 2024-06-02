<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

use App\Http\Utils\{
    ResponseHandler,
    Authorize
};

class Category extends FormRequest
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
        return (new Authorize)->checkAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'categoryName' => 'required|regex:/^[a-zA-Z0-9 ]+$/',
        ];
    }

    // public function messages()
    // {
    //     return [
    //         'categoryName.required' => 'The Category Name field is required.',
    //         'categoryName.regex' => 'The Category field is not valid.',
    //     ];
    // }

    public function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse($validator->errors(), 400)
        );
    }
}
