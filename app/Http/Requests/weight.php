<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\Password;
use App\Http\Utils\{
    ResponseHandler,
    Authorize
};

use Illuminate\Http\Request;

use App\Models\User;

class weight extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            'product_weight' => 'required|unique:weight_master,product_weight',
        ];
    }

    public function messages()
    {
        return [
            'product_weight.required' => 'The Weight field is required.',
            'product_weight.unique' => 'The Weight has already taken.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            (new ResponseHandler)->sendErrorResponse($validator->errors(), 400)
        );
    }
}
