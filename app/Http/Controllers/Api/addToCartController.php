<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    addtoCart,
    Constants,
    ProductImage,
    stockTable,
    productWeight
};
use Illuminate\Support\Facades\DB;
use App\Http\Utils\{
    ResponseHandler
};


class addToCartController extends Controller
{

    private $const;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    public function cartItemCount(Request $request)
    {
        try {
            $cartItemCount = addtoCart::orderBy('id', 'asc')->where('user_id', $request->userID)->get()->count();
            return (new ResponseHandler)->sendSuccessResponse($cartItemCount, 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function fetchCartItem(Request $request)
    {
        try {

            $fetchCartItem = addtoCart::with('product','product_weight:id,weight,specialPrice,originalPrice,discountAmount,deliveryCharge,gst')
            ->addSelect([

                'ImagePath' => ProductImage::select('ImagePath')
                ->whereColumn('product_id', 'addto_carts.product_id')
                ->where('productimages.status',1)->limit(1),

                'totalStockIn' => stockTable::select(DB::raw('SUM(stock_in)'))
                ->whereColumn('product_weight', 'addto_carts.product_weight')
                ->limit(1),

                'totalStockOut' => stockTable::select(DB::raw('SUM(stock_out)'))
                ->whereColumn('product_weight', 'addto_carts.product_weight')
                ->limit(1),

                'availableStock' => stockTable::select(DB::raw('SUM(stock_in-stock_out)'))
                ->whereColumn('product_weight', 'addto_carts.product_weight')
                ->limit(1),

            ])
            ->groupBy('addto_carts.product_id')
            ->groupBy('addto_carts.product_weight')
            ->where('addto_carts.shyamoni_order_id',null)
            ->where('addto_carts.user_id',$request->userID)
            ->get();

            return (new ResponseHandler)->sendSuccessResponse($fetchCartItem, 200);
        } catch (\Exception $e) {
            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function addCartItem(Request $request)
    {
        try {



            $checkCartItem = addtoCart::where('product_id', $request->product)
            ->where('user_id', $request->userID)
            ->where('shyamoni_order_id', NULL)
            ->where('status', 1)
            ->first();


            if($checkCartItem && $checkCartItem->product_id == $request->product && $checkCartItem->deleted_at == NULL)
                    return (new ResponseHandler)->sendErrorResponse($this->const['CART_EXISTS'], 409);


            $addCartItem = DB::table('addto_carts')->insert([
                'product_id' => $request->product,
                'user_id' => $request->userID,
                'pieces' => $request->pieces,
                'product_weight' => $request->product_weight,
                'status' => 1
            ]);


            if ($addCartItem)
                return (new ResponseHandler)->sendSuccessResponse($this->const['CART_ADDED'], 200);
        } catch (\Exception $e) {

            dd($e);

            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function deleteCartItem($id)
    {
        try {
            $deleteCartItem = addtoCart::find($id)->delete();

            if ($deleteCartItem)
                return (new ResponseHandler)->sendSuccessResponse($this->const['DELETE_CART'], 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }


}
