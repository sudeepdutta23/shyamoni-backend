<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

use App\Http\Utils\{
    ResponseHandler,
    Authorize
};

class Product extends FormRequest
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

            'Category' => 'required||numeric',
            'subCategory' => 'required||numeric',
            'productName' => 'required|regex:/^[a-zA-Z0-9 ]+$/',
            'brand' => 'required|regex:/^[a-zA-Z0-9 ]+$/',
            'shortDesc' => 'required|max:2000',
            'longDesc' => 'required',
        ];
    }

    public function messages()
    {
        return [

            'Category.required' => 'The Category field is required.',
            'subCategory.required' => 'The Sub Category field is required.',
            'productName.required' => 'The Product Name field is required.',
            'brand.required' => 'The Brand field is required.',
            'shortDesc.required' => 'The shortDesc field is required.',
            'longDesc.required' => 'The longDesc field is required.',
            'ImagePath.required' => 'The Image field is required.',

        ];
    }


    public function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse($validator->errors(), 400)
        );
    }
}
