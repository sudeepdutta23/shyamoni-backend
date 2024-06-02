<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

use App\Http\Utils\{
    ResponseHandler,
    Authorize
};

class payment extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return (new Authorize)->checkAdmin();

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

            'productName' => 'regex:/^[a-zA-Z0-9 ]+$/',
            'cart_id' => 'numeric',
            'pieces' => 'numeric',
            'price' => 'numeric',
            'product_weight' => 'numeric',
            'product_weight_id' => 'numeric',
            'deliveryCharge' => 'numeric',
            'gst' => 'numeric',

        ];
    }

    public function messages()
    {
        return [

            // 'cart_id.required' => 'The Cart is required.',
            // 'productName.required' => 'The Product is required.',
            // 'productName.required' => 'The Product Name field is required.',
            // 'brand.required' => 'The Brand field is required.',
            // 'shortDesc.required' => 'The shortDesc field is required.',
            // 'longDesc.required' => 'The longDesc field is required.',
            // 'ImagePath.required' => 'The Image field is required.',

        ];
    }


    public function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse($validator->errors(), 400)
        );
    }
}
