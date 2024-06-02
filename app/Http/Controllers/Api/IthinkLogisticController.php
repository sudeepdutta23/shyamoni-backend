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
    ProductTable,
    MailMessage
};
use App\Http\Utils\{
    ResponseHandler
};

use App\Jobs\{
    CancelOrderMailJob,
    CreateOrderMailJob,
    OrderErrorMailJob
};

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class IthinkLogisticController extends Controller
{

    private $const;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    public function createOrder($orderID,Request $request){

            try {

                $orderDetails = orderTable::where('shyamoni_order_id',$orderID)
                ->join('users','users.id','=','order_tables.user_id')
                ->select('order_tables.shyamoni_order_id','order_tables.orderDate','order_tables.totalPrice','users.name','order_tables.address_id','users.phoneNo','users.email')
                ->first();

                $order_items = addtoCart::where('shyamoni_order_id',$orderID)
                ->join('product_table','product_table.id','=','addto_carts.product_id')
                ->join('product_weight','product_weight.id','=','addto_carts.product_weight')
                ->select('product_table.productName','product_weight.product_sku','product_weight.product_sku','addto_carts.pieces',
                'product_weight.specialPrice','product_weight.discountAmount','product_weight.originalPrice')
                ->get();

                $getTotalWeight = DB::table('order_tables')
                ->where('order_tables.shyamoni_order_id',$orderID)
                ->select('product_weight')->first();

                $totalWeight =  $getTotalWeight->product_weight/1000;

                // update delivery/shipping charge based on product weight start...

                    if($getTotalWeight->product_weight <= 250){
                        orderTable::where('shyamoni_order_id',$orderID)
                        ->update([
                            'delivery_charge'=> 49
                        ]);
                    }

                    if($getTotalWeight->product_weight > 250 && $getTotalWeight->product_weight <= 500){
                        orderTable::where('shyamoni_order_id',$orderID)
                        ->update([
                            'delivery_charge'=> 59
                        ]);
                    }

                    if($getTotalWeight->product_weight <= 1000 && $getTotalWeight->product_weight > 500){
                        orderTable::where('shyamoni_order_id',$orderID)
                        ->update([
                            'delivery_charge'=> 69
                        ]);
                    }

                    if($getTotalWeight->product_weight > 1000 && $getTotalWeight->product_weight <= 2000){
                        orderTable::where('shyamoni_order_id',$orderID)
                        ->update([
                            'delivery_charge'=> 79
                        ]);
                    }

                // update delivery/shipping charge based on product weight end...

                    $orderItemsHtml = array();

                    foreach( $order_items as $key=>$value ){

                        $totalDiscount = $order_items[$key]->discountAmount * $order_items[$key]->pieces;

                        $data = [];
                        $data['product_name'] = $order_items[$key]->productName;
                        $data['product_sku']=$order_items[$key]->product_sku;
                        $data['product_quantity'] = $order_items[$key]->pieces;
                        $data['product_price'] = $order_items[$key]->originalPrice;
                        $data['product_discount'] = $totalDiscount;
                        $orderItemsHtml[] = $data;
                    }



                    $formData = [

                        "data" => [

                            "shipments" => [
                                [
                                    "waybill"=>"",
                                    "order"=> $orderDetails->shyamoni_order_id,
                                    "sub_order"=>"A",
                                    "order_date"=>$orderDetails->orderDate,
                                    "total_amount"=> $orderDetails->totalPrice,
                                    "name"=>$orderDetails->name,
                                    "company_name"=>"Shyamoni",
                                    "add"=>explode(",",$orderDetails->address_id)[0],
                                    "add2"=>explode(",",$orderDetails->address_id)[1],
                                    "add3"=>"",
                                    "pin"=> explode(",",$orderDetails->address_id)[6],
                                    "city"=> explode(",",$orderDetails->address_id)[2],
                                    "state"=> explode(",",$orderDetails->address_id)[4],
                                    "country"=> explode(",",$orderDetails->address_id)[5],
                                    "phone"=>$orderDetails->phoneNo,
                                    "alt_phone"=>"",
                                    "email"=>$orderDetails->email,
                                    "is_billing_same_as_shipping"=>"no",
                                    "billing_name"=>$orderDetails->name,
                                    "billing_company_name"=>"Shyamoni",
                                    "billing_add"=> explode(",",$orderDetails->address_id)[0],
                                    "billing_add2"=> explode(",",$orderDetails->address_id)[1],
                                    "billing_add3"=>"",
                                    "billing_pin" => explode(",",$orderDetails->address_id)[6],
                                    "billing_city"=> explode(",",$orderDetails->address_id)[2],
                                    "billing_state"=> explode(",",$orderDetails->address_id)[4],
                                    "billing_country"=> explode(",",$orderDetails->address_id)[5],
                                    "billing_phone"=> $orderDetails->phoneNo,
                                    "billing_alt_phone"=>"",
                                    "billing_email"=> $orderDetails->email,
                                    "products"=>$orderItemsHtml,
                                    "shipment_length" => "10",    #in cm
                                    "shipment_width" => "10",    #in cm
                                    "shipment_height" => "5",    #in cm
                                    "weight" => $totalWeight,    #in Kg
                                    "shipping_charges" => "0",
                                    "giftwrap_charges" => "0",
                                    "transaction_charges" => "0",
                                    "total_discount" => "0",
                                    "first_attemp_discount" => "0",
                                    "cod_charges" => "0",
                                    "advance_amount" => "0",
                                        "cod_amount" => "",
                                    "payment_mode" => "COD",    #For reverse Shipments=> Prepaid Only
                                    "reseller_name" => "",
                                    "eway_bill_number" => "",
                                    "gst_number" => "",
                                    // "return_address_id" => 1293
                                    "return_address_id" => 29590

                                ]
                            ],
                            // "pickup_address_id" => 1293,
                            "pickup_address_id" => 29590,
                            "access_token" => env("Access_Token"), #You will get this from iThink Logistics Team
                            "secret_key" => env("Secret_Key"), #You will get this from iThink Logistics Team

                            "logistics" => "Delhivery",  #Allowed values delhivery, fedex, xpressbees, ecom, ekart. For reverse Shipments=> Delhivery Only
                            "s_type" => "", #If fedex than Allowed values standard, priority, ground.
                            "order_type" => "" #If placing reverse shipment, pass 'reverse' else can be left blank.
                        ],

                    ];


                    // Calling Guzzle Component

                    // $orderSuccess = GuzzleComponent('POST',env("logisticTestURL").'order/add.json',$formData);

                    // return GuzzleComponent('POST',env("logisticProductionURL").'order/add.json',$formData);

                    $orderSuccess = GuzzleComponent('POST',env("logisticProductionURL").'order/add.json',$formData);


                    if($orderSuccess['status']=='success'){

                        $insertSuccessResponse = DB::table('ithink_order_fail_logs')
                        ->insert([
                            'user_id'=>$request->userID,
                            'reason'=>$orderSuccess['data'][1]['remark'],
                            'refnum'=>$orderSuccess['data'][1]['refnum'],
                            'logistic_name'=>$orderSuccess['data'][1]['logistic_name'],
                            'response'=> json_encode($orderSuccess),
                            'status'=>$orderSuccess['data'][1]['status'],
                        ]);

                        if($insertSuccessResponse){

                            DB::table('order_tables')
                            ->where('shyamoni_order_id',$orderID)
                            ->update([
                                'awb_id'=>$orderSuccess['data'][1]['waybill'],
                            ]);


                            $fetchMsg = MailMessage::where('constant',env("createOrder_constant"))->first();


                            if($fetchMsg){

                                $CreateOrderMailJob =  CreateOrderMailJob::dispatch((new MailController)->createOrderMail($request,$fetchMsg,$orderID));

                                if($CreateOrderMailJob){

                                    return (new ResponseHandler)->sendSuccessResponse($this->const['WEB_HOOK_PAYMENT_CAPTURED'], 200);

                                }

                            }

                        }
                    }
                    else
                    {

                        DB::table('ithink_order_fail_logs')
                        ->insert([
                            'user_id'=>$request->userID,
                            'reason'=>$orderSuccess['data'][1]['remark'],
                            'refnum'=>$orderSuccess['data'][1]['refnum'],
                            'logistic_name'=>$orderSuccess['data'][1]['logistic_name'],
                            'response'=> json_encode($orderSuccess),
                            'status'=>$orderSuccess['data'][1]['status'],
                        ]);

                        $fetchMsg = MailMessage::where('constant',env("order_error_constant"))->first();

                        OrderErrorMailJob::dispatch((new MailController)->OrderErrorMail($request,$fetchMsg,$orderID));

                        $deleteOrder = orderTable::where('shyamoni_order_id',$orderID)->delete();

                        if($deleteOrder)

                            return (new ResponseHandler)->sendErrorResponse($this->const['ORDER_FAILED'], 500);


                    }





            } catch (\Exception $e) {
                    dd($e);
                return (new ResponseHandler)->sendErrorResponse();
            }




    }

    public function createLocalOrder($orderID){

        try{

            orderTable::where('shyamoni_order_id',$orderID)
            ->update(['is_local'=> 1]);


        }catch (\Exception $e)
        {
            return (new ResponseHandler)->sendErrorResponse();

        }


    }


    public function trackOrder($awb_id){

        try{


            $formData = [
                "data"=>[

                    "awb_number_list" => $awb_id,
                    "access_token" => env("Access_Token"), #You will get this from iThink Logistics Team
                    "secret_key" => env("Secret_Key"), #You will get this from iThink Logistics Team

                ]
            ];

            // Calling Guzzle Component
                return GuzzleComponent('POST',env("logisticProductionURL").'order/track.json',$formData);


        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }

    }


    public function cancelOrder($getAwbID){

        try{


            $formData = [

                "data"=>[

                    "access_token" => env("Access_Token"), #You will get this from iThink Logistics Team
                    "secret_key" => env("Secret_Key"), #You will get this from iThink Logistics Team
                    "awb_numbers" => $getAwbID,

                ]
            ];


            // Calling Guzzle Component
            return GuzzleComponent('POST',env("logisticProductionURL").'order/cancel.json',$formData);


        }
        catch(\Exception $e){
            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }

    }

    public function getState(){

        try{


            $formData = [
                    "data"=>[

                        "country_id" => "101",
                        "access_token" => env("Access_Token"), #You will get this from iThink Logistics Team
                        "secret_key" => env("Secret_Key"), #You will get this from iThink Logistics Team

                ]
            ];

             // Calling Guzzle Component
                return GuzzleComponent('POST',env("logisticProductionURL").'state/get.json',$formData);


        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function getCity($state_id){

        try{


            $formData = [

                "data"=>[

                    "state_id" => $state_id,       #country_id 101 is for india. you will get all states in india.
                    "access_token" => env("Access_Token"), #You will get this from iThink Logistics Team
                    "secret_key" => env("Secret_Key"), #You will get this from iThink Logistics Team

                ]
            ];

            // Calling Guzzle Component
            return GuzzleComponent('POST',env("logisticProductionURL").'city/get.json',$formData);


        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }

    }

    public function checkPinCode($pincode){


        try{



            $formData = [
                "data"=>[
                    "pincode"  => $pincode,
                    "access_token" => env("Access_Token"), #You will get this from iThink Logistics Team
                    "secret_key" => env("Secret_Key"), #You will get this from iThink Logistics Team

                ]
            ];

            // Calling Guzzle Component
            return GuzzleComponent('POST',env("logisticProductionURL").'pincode/check.json',$formData);

        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }

    }


    public function trackOrderStatus($orderid,$request){


                    $getAwbID = orderTable::where('shyamoni_order_id', $orderid)->first();

                    $shyamoniOrderID = $getAwbID->shyamoni_order_id;

                    $tracking_data = (new IthinkLogisticController)->cancelOrder($getAwbID->awb_id);

                    if($tracking_data['status']=='error'){

                        return (new ResponseHandler)->sendErrorResponse($tracking_data, 500);

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





}
