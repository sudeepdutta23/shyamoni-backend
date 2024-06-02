<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Utils\{
    ResponseHandler,
    Authorize
};

class SubCategory extends FormRequest
{
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

            'Category' => 'required|numeric',
            'subCategory_name' => 'required|regex:/^[a-zA-Z0-9 ]+$/',
        ];
    }

    public function messages()
    {
        return [

            'Category.required' => 'The Category field is required.',
            'subCategory_name.required' => 'The Sub Category field is required.',
        ];
    }




    public function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse($validator->errors(), 400)
        );
    }
}
