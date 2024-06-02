<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Utils\{
    ResponseHandler
};

use App\Models\{
    productWeight,
    Constants
};


class WeightController extends Controller
{


    private $const;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    public function fetchProductWeight(Request $request)
    {
        try {
            $fetchProductWeight = productWeight::orderBy('id', 'asc')->get();
            return (new ResponseHandler)->sendSuccessResponse($fetchProductWeight, 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }


    public function addProductWeight(Request $request)
    {
        try {
            $addProduct = productWeight::create([
                'product_id' => $request->product,
                'product_weight' => $request->product_weight,
                'product_price' => $request->product_price,
                'product_discount' => $request->product_discount,
                'product_coupons' => $request->product_coupons,
                'product_coupons_expiryDate' => $request->product_coupons_expiryDate,
            ]);

            if ($addProduct)
                return (new ResponseHandler)->sendSuccessResponse($this->const['PRODUCT_WEIGHT_ADD'], 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function editProductWeight(Request $request, $id)
    {
        try {
            $updateProduct = productWeight::where('id', $id)->update([
                'product_id' => $request->product,
                'product_weight' => $request->product_weight,
                'product_price' => $request->product_price,
                'product_discount' => $request->product_discount,
                'product_coupons' => $request->product_coupons,
                'product_coupons_expiryDate' => $request->product_coupons_expiryDate,
            ]);
            if ($updateProduct)
                return (new ResponseHandler)->sendSuccessResponse($this->const['PRODUCT_WEIGHT_UPDATE'], 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }


    public function deleteProductWeight($id)
    {
        try {
            $deleteProduct = productWeight::find($id)->delete();
            if ($deleteProduct)
                return (new ResponseHandler)->sendSuccessResponse($this->const['PRODUCT_WEIGHT_DELETE'], 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }
}
