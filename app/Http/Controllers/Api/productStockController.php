<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\stockTable;
use App\Models\{
    ProductTable,
    ProductImage,
    Constants,
    logConstants
};

use App\Http\Utils\{
    ResponseHandler
};


use App\Http\Requests\{
    ProductStock
};
use DB;
use Illuminate\Support\Facades\Auth;


class productStockController extends Controller
{


    private $const,$logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();

        $this->logConst = (new logConstants)->getLogConstants();

    }

    public function fetchProductStock(Request $request)
    {
        try {

            $fetchProductStock = DB::table('stock_tables')
            ->join('product_table','product_table.id','=','stock_tables.product_id')
            ->join('product_weight','product_weight.id','=','stock_tables.product_weight')
            ->select('stock_tables.id','stock_tables.product_id','product_weight.weight',
            'product_table.productName',
                DB::raw('SUM(stock_tables.stock_in) as totalStockIn'),
                DB::raw('SUM(stock_tables.stock_out) as totalStockOut'),
                DB::raw('SUM(stock_in-stock_out) as availableStock')
                )
            ->groupBy('product_weight.weight')
            ->groupBy('stock_tables.product_id')
            ->where('stock_tables.deleted_at','=',NULL)
            ->get();


            return (new ResponseHandler)->sendSuccessResponse($fetchProductStock, 200);
        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }
    }


    public function addProductStock(ProductStock $request)
    {
        try {

                $checkAvailableStock = DB::table('stock_tables')
                ->join('product_weight','product_weight.id','=','stock_tables.product_weight')
                ->select(
                    DB::raw('SUM(stock_tables.stock_in) as totalStockIn'),
                    DB::raw('SUM(stock_tables.stock_out) as totalStockOut'),
                    DB::raw('SUM(stock_in-stock_out) as availableStock'),
                    'stock_tables.product_weight'
                    )
                ->where('stock_tables.product_id',$request->product)
                ->groupBy('stock_tables.product_id')
                ->get();


                if(empty($checkAvailableStock->toArray())){

                    $addProductStock = DB::table('stock_tables')->insert([
                        'product_id' => $request->product,
                        'stock_in' => $request->stock_in ? $request->stock_in : 0,
                        'stock_out' => $request->stock_out ? $request->stock_out : 0,
                        'product_weight' => $request->product_weight,
                        'stock_status' => 1,
                        'status' => 1,
                    ]);

                    if ($addProductStock)

                            return (new ResponseHandler)->sendSuccessResponse($this->const['PRODUCT_STOCK_ADD'], 200);

                }else
                {

                    // if(($request->stock_in + $request->stock_out) > $checkAvailableStock[0]->availableStock && $checkAvailableStock[0]->product_weight == $request->product_weight){
                    if(($request->stock_out) > $checkAvailableStock[0]->availableStock && $checkAvailableStock[0]->product_weight == $request->product_weight){

                        return (new ResponseHandler)->sendErrorResponse($this->const['STOCK_ERROR'], 500);

                    }else
                    {
                        $addProductStock = DB::table('stock_tables')->insert([
                            'product_id' => $request->product,
                            'stock_in' => $request->stock_in ? $request->stock_in : 0,
                            'stock_out' => $request->stock_out ? $request->stock_out : 0,
                            'product_weight' => $request->product_weight,
                            'stock_status' => 1,
                            'status' => 1,
                        ]);

                        if ($addProductStock)

                            $message = "Product Stock added by {{username}}";

                            $string = str_replace('{{username}}',auth('sanctum')->user()->name, $message);

                            DB::table('activity_logs')->insert([
                                'user_id'=>auth('sanctum')->user()->id,
                                'activity'=>$string,
                                'requestResponse'=>json_encode($request->all()),
                            ]);

                            return (new ResponseHandler)->sendSuccessResponse($this->const['PRODUCT_STOCK_ADD'], 200);
                    }

                }


        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }
    }


    public function getStockByProduct(Request $request){

        try {

            $getStockByProduct = DB::table('stock_tables')
            ->join('product_table','product_table.id','=','stock_tables.product_id')
            ->join('product_weight','product_weight.id','=','stock_tables.product_weight')
            ->where('stock_tables.product_id',$request->product_id)
            ->get();

            return (new ResponseHandler)->sendSuccessResponse($getStockByProduct, 200);
        } catch (\Exception $e) {
            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }

    }

    // public function editProductStock(ProductStock $request, $id)
    // {
    //     try {

    //         $editProductStock = stockTable::where('id', $id)->update([
    //             'product_id' => $request->product,
    //             'stock_in' => $request->stock_in,
    //             'stock_out' => $request->stock_out,
    //         ]);

    //         if ($editProductStock)
    //             return (new ResponseHandler)->sendSuccessResponse($this->const['PRODUCT_STOCK_UPDATE'], 200);
    //     } catch (\Exception $e) {
    //         return (new ResponseHandler)->sendErrorResponse();
    //     }
    // }


    // public function deleteProductStock($id)
    // {
    //     try {

    //         $deleteProductStock = stockTable::find($id)
    //         // dd($deleteProductStock);
    //         ->delete();
    //         if ($deleteProductStock)
    //             return (new ResponseHandler)->sendSuccessResponse($this->const['PRODUCT_STOCK_DELETE'], 200);
    //     } catch (\Exception $e) {

    //         return (new ResponseHandler)->sendErrorResponse();
    //     }
    // }

}
