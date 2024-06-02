<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Constants,
    logConstants,
    orderTable,
    userAddress,
    addtoCart,
    PaymentStatus,
    ProductTable,
    MailMessage
};


use App\Jobs\{
    CancelOrderMailJob,
};


use App\Http\Utils\{
    ResponseHandler
};
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\DeliveryVendorController;
use App\Http\Controllers\Api\IthinkLogisticController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    private $const,$logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();

        $this->logConst = (new logConstants)->getLogConstants();

    }

    public function getAllOrders(Request $request)
    {

        try {

            $getAllOrders = orderTable::where('user_id', $request->userID)
            ->select('order_tables.id', 'order_tables.shyamoni_order_id as order_id',
            'order_tables.totalPrice', 'order_tables.orderDate', 'order_tables.paymentDone',
            'order_tables.awb_id','order_tables.is_local','order_tables.is_cancel')
            ->where('paymentDone','=','captured')
            ->orderBy('order_tables.id','Desc')
            ->get();


            $myArray = [];

            foreach($getAllOrders as $key=>$value){



                $abc = $this->getOrderDetailsByID($request,$value->toArray()['order_id']);

                array_push($myArray,
                     $abc->original['data'],
                    );

            }

            if ($getAllOrders)
                return (new ResponseHandler)->sendSuccessResponse($myArray, 200);
        } catch (\Exception $e) {

                dd($e);

            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function fetchAllOrders(Request $request)
    {
        try {

                $getAllOrders = DB::table('order_tables')
                ->where('deleted_at','=',NULL)
                ->where('paymentDone','=','captured')
                ->join('users', 'users.id', '=', 'order_tables.user_id')
                ->select('order_tables.id', 'order_tables.shyamoni_order_id as order_id', 'order_tables.totalPrice', 'order_tables.orderDate', 'order_tables.paymentDone','order_tables.awb_id','users.name','order_tables.user_id','order_tables.address_id','order_tables.is_local','order_tables.is_cancel')
                ->orderBy('id','desc')
                ->get();

            if ($getAllOrders)
                return (new ResponseHandler)->sendSuccessResponse(['getAllOrders' => $getAllOrders], 200);
        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function getOrderDetailsByID(Request $request,$id)
    {

        try {


            $getProductDetails = DB::table('order_tables')
                ->join('addto_carts', 'addto_carts.shyamoni_order_id', '=', 'order_tables.shyamoni_order_id')
                ->join('product_weight','product_weight.id','=','addto_carts.product_weight')
                ->join('product_table', 'product_table.id', '=', 'addto_carts.product_id')
                ->where('order_tables.shyamoni_order_id', $id)
                ->where('addto_carts.shyamoni_order_id', '!=', NULL)
                ->where('order_tables.deleted_at', '=', NULL)
                ->where('order_tables.paymentDone','=','captured')
                ->where('product_table.product_status',1)
                ->select(
                    'addto_carts.id as cart_id',
                    'addto_carts.pieces',
                    'product_table.productName',
                    'product_weight.specialPrice',
                    'product_weight.originalPrice',
                    'product_weight.discountAmount',
                     DB::raw('(case when (product_weight.weight > 999) then CONCAT(product_weight.weight/1000,"kg") else CONCAT(product_weight.weight,"g") end) as weight'),
                    'product_weight.deliveryCharge',
                    'product_weight.gst',
                    'product_table.shortDesc',
                    'product_table.longDesc',
                    'product_table.id',
                        DB::raw("(" .
                        'select ImagePath from productimages where productimages.product_id = product_table.id
                        group by product_table.id,productimages.product_id'
                        . ") as productImage")

            )->get();

            $calculateDiscount = 0;
            $calculateGST = 0;
            $deliveryCharge = 0;

            foreach ($getProductDetails as $key => $value) {

                $calculateDiscount += $getProductDetails[$key]->pieces * $getProductDetails[$key]->discountAmount;

                $calculateGST +=  $getProductDetails[$key]->specialPrice * $getProductDetails[$key]->pieces * ($getProductDetails[$key]->gst/100);

                $deliveryCharge += $getProductDetails[$key]->deliveryCharge;
            }


            $Price = DB::table('order_tables')
            ->where('order_tables.shyamoni_order_id', $id)
            ->where('order_tables.user_id', $request->userID)
            ->select('totalPrice')->first()->totalPrice;

            $totalPrice = $Price + $calculateGST + $deliveryCharge;


            $getOrderDetailsByID = DB::table('order_tables')
            ->join('addto_carts', 'addto_carts.shyamoni_order_id', '=', 'order_tables.shyamoni_order_id')
            ->where('order_tables.shyamoni_order_id', $id)
            ->where('addto_carts.shyamoni_order_id', '!=', NULL)
            ->where('order_tables.deleted_at', '=', NULL)
            ->where('order_tables.paymentDone','=','captured')
            ->select('order_tables.shyamoni_order_id as order_id',
            'order_tables.paymentDone','order_tables.orderDate','order_tables.awb_id','order_tables.is_local',
            'order_tables.is_cancel','order_tables.address_id as address','order_tables.receiptId as InvoiceID',
             DB::raw("'$calculateDiscount' as totalDiscount"),DB::raw("'$calculateGST' as totalGST"),
             DB::raw("'$deliveryCharge' as totalDeliveryCharge"),DB::raw("'$totalPrice' as totalPrice"))
            ->first();


            $getDiscountPrice = DB::table('order_tables')
                ->join('addto_carts', 'addto_carts.shyamoni_order_id', '=', 'order_tables.shyamoni_order_id')
                ->join('product_weight','product_weight.id','=','addto_carts.product_weight')
                ->where('order_tables.deleted_at', '=', NULL)
                ->where('order_tables.paymentDone','=','captured')
                ->where('addto_carts.shyamoni_order_id', '!=', NULL)
                ->where('order_tables.shyamoni_order_id', $id)
                ->select('addto_carts.pieces', 'product_weight.discountAmount as dicount_price')
                ->get();


            $discountPrice = 0;

            foreach ($getDiscountPrice as $key => $value) {
                $discountPrice += $getDiscountPrice[$key]->pieces * $getDiscountPrice[$key]->dicount_price;
            }


            if ($getOrderDetailsByID)
                return (new ResponseHandler)->sendSuccessResponse(['getOrderDetailsByID' =>$getOrderDetailsByID,'getProductDetails'=>$getProductDetails], 200);


        } catch (\Exception $e) {
            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function updateAWB(Request $request)
    {
        try {


            $updateAWB = orderTable::where('user_id', $request->user_id)
                ->where('shyamoni_order_id',$request->orderID)
                ->update([
                    'awb_id' => $request->awb_id
                ]);

            if ($updateAWB)

                return (new ResponseHandler)->sendSuccessResponse($this->const['UPDATE_AWB_CODE'], 201);
        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function trackOrder($awb_id)
    {
        try {

            $trackOrder = (new DeliveryVendorController)->trackOrder($awb_id);

            return (new ResponseHandler)->sendSuccessResponse(['trackOrder' => $trackOrder], 200);

        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function cancelOrder(Request $request, $orderid)
    {

        try {


           return  (new DeliveryVendorController)->cancelOrder($orderid,$request);

            // $getAwbID = orderTable::where('shyamoni_order_id', $orderid)->first();

            // $shyamoniOrderID = $getAwbID->shyamoni_order_id;


            // if ($getAwbID->awb_id != null) {


            //     if(env('vendorName') == 'ithink'){

            //         $tracking_data = (new IthinkLogisticController)->cancelOrder($getAwbID->awb_id);

            //         if($tracking_data['status']=='error'){

            //             return (new ResponseHandler)->sendErrorResponse($tracking_data, 500);

            //         }else
            //         {
            //                 // If Order Cancelled, should send cancellation Success mail to customer mail
            //                 $fetchMsg = MailMessage::where('constant',env("cancelOrder_Mail_constant"))
            //                 ->first();

            //                 CancelOrderMailJob::dispatch((new MailController)->cancelOrderMail($fetchMsg,$request,$shyamoniOrderID));

            //                 DB::table('order_tables')
            //                 ->where('awb_id',$getAwbID->awb_id)
            //                 ->where('shyamoni_order_id',$shyamoniOrderID)
            //                 ->update([
            //                     'is_cancel' => 1,
            //                 ]);

            //                 return (new ResponseHandler)->sendSuccessResponse($this->const['ORDER_CANCEL_ACCEPT'], 200);

            //         }

            //     }else
            //     {

            //         $tracking_data = (new ShipRocketController)->cancelOrder($getAwbID);

            //         if ($tracking_data['tracking_data']['shipment_status'] < 8){

            //             return (new ResponseHandler)->sendErrorResponse($this->const['ORDER_CANCEL_REJECT'], 500);


            //         }else
            //         {
            //             // If Order Cancelled, should send cancellation Success mail to customer mail
            //             $fetchMsg = MailMessage::where('constant',env("cancelOrder_Mail_constant"))
            //             ->first();

            //             CancelOrderMailJob::dispatch((new MailController)->cancelOrderMail($fetchMsg,$request,$shyamoniOrderID));

            //             DB::table('order_tables')
            //             ->where('awb_id',$getAwbID->awb_id)
            //             ->where('shyamoni_order_id',$shyamoniOrderID)
            //             ->update([
            //                 'is_cancel' => 1,
            //             ]);

            //             return (new ResponseHandler)->sendSuccessResponse($this->const['ORDER_CANCEL_ACCEPT'], 200);

            //         }

            //     }

            // }else
            // {
            //     return (new ResponseHandler)->sendErrorResponse($this->const['ORDER_CANCEL_REJECT'], 500);

            // }


        } catch (\Exception $e) {
            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function getState(Request $request){

        try {

            $getState = (new DeliveryVendorController)->getState();

            if($getState['status']=='error'){
                return (new ResponseHandler)->sendErrorResponse($getState, 500);
            }else
            {
                return (new ResponseHandler)->sendSuccessResponse(['getState' => $getState], 200);
            }

        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }

    }

    public function getCity(Request $request){

        try {

                $getCity = (new DeliveryVendorController)->getCity($request->state_id);

                if($getCity['status']=='error'){
                    return (new ResponseHandler)->sendErrorResponse($getCity, 500);
                }else
                {
                    return (new ResponseHandler)->sendSuccessResponse(['getCity' => $getCity], 200);
                }

            } catch (\Exception $e) {

                return (new ResponseHandler)->sendErrorResponse();
            }

    }

    public function checkPinCode(Request $request){

        try{


            $pincode = $request->pincode;

            $checkPinCode = (new DeliveryVendorController)->checkPinCode($pincode);

            if($checkPinCode['status']=='error'){
                return (new ResponseHandler)->sendErrorResponse($checkPinCode, 500);
            }else
            {
                return (new ResponseHandler)->sendSuccessResponse(['checkPinCode' => $checkPinCode], 200);
            }


        }catch (\Exception $e){

            return (new ResponseHandler)->sendErrorResponse();


        }

    }
}
