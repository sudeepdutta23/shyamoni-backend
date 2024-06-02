<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;

use App\Http\Utils\{
    ResponseHandler
};

class paymentRequest extends FormRequest
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

        $user_role =  explode(",",env('USER_ROLE'));
        if(in_array(Auth::user()->role_id, $user_role))
            return true;
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cart_id' => 'required||numeric',
            'product'=> 'required',
            'productName'=> 'required||regex:/^[a-zA-Z0-9 ]+$/',
            'pieces'=>'required||numeric',
            'price'=>'required||numeric'
        ];
    }

    public function messages()
    {
        return[
            'cart_id.required' => 'The Category field is required.',
            'product.required' => 'The Product field is required.',
            'productName.required' => 'The Product Name field is required.',
            'pieces.required' => 'The Pieces field is required.',
            'price.required' => 'The Price field is required.',
        ];
    }

        public function failedValidation(Validator $validator) {

            throw new HttpResponseException(
                (new ResponseHandler)->sendErrorResponse($validator->errors(), 400)
            );
        }

}
