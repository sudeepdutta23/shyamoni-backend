<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product;
use Illuminate\Http\Request;
use App\Models\DeliveryVendor;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
use App\Models\{
    userAddress,
    orderTable,
    addtoCart,
    CreateShippingOrder,
    Constants,
    MailMessage
};
use App\Http\Utils\{
    ResponseHandler
};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Jobs\{
    CancelOrderMailJob,
    CreateOrderMailJob,
    OrderErrorMailJob
};


class ShipRocketController extends Controller
{

    private $const;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    public function createOrder($orderID,$request){

        try{

                $PickupLocation = env("PickupLocation");
                $payment_method = env("payment_method");
                $length=10;
                $breadth=10;
                $height=10;

                $getTotalPrice = DB::table('order_tables')
                ->where('shyamoni_order_id',$orderID)
                ->where('shyamoni_order_id','!=',NULL)
                ->select('totalPrice')->first();

                $getTotalWeight = DB::table('order_tables')
                ->where('shyamoni_order_id',$orderID)
                ->where('shyamoni_order_id','!=',NULL)
                ->select('product_weight')->first();


                $totalWeight =  $getTotalWeight->product_weight/1000;


                $subTotal = ($getTotalPrice->totalPrice);


                $getOrderDetails = orderTable::where('shyamoni_order_id',$orderID)
                ->where('order_tables.shyamoni_order_id','!=',NULL)
                ->join('users','users.id','=','order_tables.user_id')
                ->select('order_tables.shyamoni_order_id','order_tables.orderDate','users.name','order_tables.user_id','users.email','users.phoneNo')
                ->first();


                $getAddressDetails = DB::table('order_tables')
                ->join('users','users.id','=','order_tables.user_id')
                ->where('user_id',$getOrderDetails->user_id)
                ->where('shyamoni_order_id',$orderID)
                ->select('address_id','users.email','users.phoneNo','users.name')
                ->get();


                $order_items = addtoCart::where('shyamoni_order_id',$orderID)
                ->where('addto_carts.user_id',$getOrderDetails->user_id)
                ->join('product_table','product_table.id','=','addto_carts.product_id')
                ->join('product_weight','product_weight.id','=','addto_carts.product_weight')
                ->select('product_table.productName','addto_carts.pieces','product_weight.specialPrice','product_weight.product_sku')
                ->get();


                $orderItemsHtml = array();

                foreach( $order_items as $value ){
                    $data = [];
                    $data['name'] = $value->productName;
                    $data['sku']=$value->product_sku;
                    $data['units'] = $value->pieces;
                    $data['selling_price'] = $value->specialPrice;
                    $orderItemsHtml[] = $data;
                }

                $form_params = [

                    "order_id"              => $getOrderDetails['shyamoni_order_id'],
                    "order_date"            =>  $getOrderDetails['orderDate'],
                    "pickup_location"       => $PickupLocation,
                    "channel_id"            => "",
                    "comment"               => "Order created from order list page",
                    "billing_customer_name" => $getOrderDetails['name'],
                    "billing_last_name"     => "",
                    "billing_address"       => explode(",",$getAddressDetails[0]->address_id)[0],
                    "billing_address_2"     =>  explode(",",$getAddressDetails[0]->address_id)[1],
                    "billing_city"          => explode(",",$getAddressDetails[0]->address_id)[2],
                    "billing_pincode"       => explode(",",$getAddressDetails[0]->address_id)[6],
                    "billing_state"         => explode(",",$getAddressDetails[0]->address_id)[4],
                    "billing_country"       => explode(",",$getAddressDetails[0]->address_id)[5],
                    "billing_email"         => $getAddressDetails[0]->email,
                    "billing_phone"         => $getAddressDetails[0]->phoneNo,
                    "shipping_is_billing"   => false,
                    "shipping_customer_name"=> $getAddressDetails[0]->name,
                    "shipping_last_name"    =>"qqqq",
                    "shipping_address"      => explode(",",$getAddressDetails[0]->address_id)[0],
                    "shipping_address_2"    => explode(",",$getAddressDetails[0]->address_id)[1],
                    "shipping_city"         => explode(",",$getAddressDetails[0]->address_id)[2],
                    "shipping_pincode"      => explode(",",$getAddressDetails[0]->address_id)[6],
                    "shipping_country"      => explode(",",$getAddressDetails[0]->address_id)[5],
                    "shipping_state"        => explode(",",$getAddressDetails[0]->address_id)[4],
                    "shipping_email"        => $getAddressDetails[0]->email,
                    "shipping_phone"        => $getAddressDetails[0]->phoneNo,
                    "order_items"           => $orderItemsHtml,
                    "payment_method"        =>$payment_method,
                    "shipping_charges"      => 0,
                    "sub_total"             => "$subTotal",
                    "total_discount"        => 0,
                    "length"                => "$length",
                    "breadth"               => "$breadth",
                    "height"                => "$height",
                    "weight"                => "$totalWeight"
                ];


                orderTable::where('shyamoni_order_id',$orderID)
                ->update(['is_local'=> 2]);


                $shiprocketResponse = shipRoketGuzzlePostComponent('POST',env("shiprocketURL").'orders/create/adhoc',$form_params);



                if($shiprocketResponse['status_code'] == 1)
                {
                        DB::table('create_shipping_orders')->insert([
                            'order_id'=>$shiprocketResponse['order_id'],
                            'shyamoni_order_id'=>$getOrderDetails['shyamoni_order_id'],
                            'shipment_id'=>$shiprocketResponse['shipment_id'],
                            'status'=>$shiprocketResponse['status'],
                            'status_code'=>$shiprocketResponse['status_code'],
                            'onboarding_completed_now'=>$shiprocketResponse['onboarding_completed_now'],
                            'awb_code'=>$shiprocketResponse['awb_code'],
                            'courier_company_id'=>$shiprocketResponse['courier_company_id'],
                            'courier_name'=>$shiprocketResponse['courier_name'],
                            'response'=>json_encode($shiprocketResponse),
                            'userID'=>$request->userID,

                        ]);

                }

        }catch (\Exception $e)
        {
                dd($e);
                $errSubject = env('errSubject');

                DB::table('create_shipping_orders')->insert([
                    'shyamoni_order_id'=>$orderID,
                    'response'=>$e->getMessage(),
                    'userID'=>$request->userID,
                ]);

                $fetchMsg = MailMessage::where('constant',env("order_error_constant"))->first();

                OrderErrorMailJob::dispatch((new MailController)->OrderErrorMail($request,$fetchMsg,$orderID,$e->getTraceAsString(),$errSubject));

                orderTable::where('shyamoni_order_id',$orderID)->delete();

                return response()->json(['err_msg'=>$shiprocketResponse['message']]);


        }



    }


    public function createLocalOrder($orderID){


        // orderTable::where('order_id',$orderID)
        // ->update(['is_local'=> 1]);

        orderTable::where('shyamoni_order_id',$orderID)
        ->update(['is_local'=> 1]);


    }


    public function trackOrder($awb_id){

        try{

            $getToken = getToken();

            $client = GuzzleClient();

            $response  =  $client->get('https://apiv2.shiprocket.in/v1/external/courier/track/awb/'.$awb_id,
            [
                "headers"=>[
                    "Authorization"=>"Bearer " .$getToken,
                    "Content-Type"=> "application/json",
                ],
            ]);

            $tracking_data  =  json_decode($response->getBody(), true);

            return $tracking_data;

        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }

    }

    public function cancelOrder($getAwbID){

        try{

                $getToken = getToken();

                $client = GuzzleClient();

                $response  =  $client->get('https://apiv2.shiprocket.in/v1/external/courier/track/awb/'.$getAwbID,[
                "headers"=>[
                    "Authorization"=>"Bearer " .$getToken,
                    "Content-Type"=> "application/json",
                ],
            ]);

                return json_decode($response->getBody(), true);
        }
        catch(\Exception $e){
            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }

    }


    public function trackOrderStatus($orderid,$request){


        $getAwbID = orderTable::where('shyamoni_order_id', $orderid)->first();

        $shyamoniOrderID = $getAwbID->shyamoni_order_id;


        if ($getAwbID->awb_id != null) {

                $tracking_data = (new ShipRocketController)->cancelOrder($getAwbID);

            if($tracking_data['tracking_data']['error']){

                    return (new ResponseHandler)->sendErrorResponse($tracking_data['tracking_data']['error'], 500);

                }else
                {

                    if ($tracking_data['tracking_data']['shipment_status'] < 8){

                        return (new ResponseHandler)->sendErrorResponse($this->const['ORDER_CANCEL_REJECT'], 500);


                    }else
                    {
                        // If Order Cancelled, should send cancellation Success mail to customer mail
                        $fetchMsg = MailMessage::where('constant',env("cancelOrder_Mail_constant"))
                        ->first();

                        CancelOrderMailJob::dispatch((new MailController)->cancelOrderMail($fetchMsg,$request,$shyamoniOrderID));

                        DB::table('order_tables')
                        ->where('awb_id',$getAwbID->awb_id)
                        ->where('shyamoni_order_id',$shyamoniOrderID)
                        ->update([
                            'is_cancel' => 1,
                        ]);

                        return (new ResponseHandler)->sendSuccessResponse($this->const['ORDER_CANCEL_ACCEPT'], 200);

                    }


                }



        }else
        {
            return (new ResponseHandler)->sendErrorResponse($this->const['ORDER_CANCEL_REJECT'], 500);

        }


    }






}
