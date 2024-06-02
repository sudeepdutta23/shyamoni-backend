<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

use App\Http\Utils\{
    ResponseHandler,
    Authorize
};

class ProductStock extends FormRequest
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

            'product' =>  'required',
            'stock_in' =>  'numeric',
        ];
    }

    public function messages()
    {
        return[

            'product.required' => 'Produc Field is required.',
            'stock_in.required' => 'Stock IN field is required.',
            'stock_out.required' => 'Stock Out field is required.',
            'stock_in.numeric' => 'Stock IN field must be number.',
            'stock_out.numeric' => 'Stock Out field must be number',


        ];
    }

        public function failedValidation(Validator $validator) {

            throw new HttpResponseException(
                (new ResponseHandler)->sendErrorResponse($validator->errors(), 400)
            );
        }

}
