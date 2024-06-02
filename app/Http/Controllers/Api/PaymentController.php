<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use Illuminate\Support\Str;

use App\Models\{
    Constants,
    addtoCart,
    User,
    orderTable,
    stockTable,
    userAddress,
    MailMessage,
    ProductTable
};

use App\Jobs\{
    CreateOrderMailJob,
    OrderErrorMailJob,
};

use Illuminate\Support\Carbon;

use App\Http\Utils\{
    ResponseHandler
};


use Illuminate\Support\Facades\Hash;

use Razorpay\Api\Errors\SignatureVerificationError;

use App\Http\Controllers\Api\DeliveryVendorController;
use App\Http\Controllers\Api\MailController;
use Auth;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Facades\Invoice as FacadesInvoice;


use App\Http\Requests\{
    payment,
};

class PaymentController extends Controller
{

    private $const;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    public function payment(payment $request)
    {
        try {

                      $allObject = json_decode($request->getContent(), true);



                        if(!$allObject[0]['address_id'])
                            return (new ResponseHandler)->sendErrorResponse($this->const['SELECT_ADDRESS'], 400);

                            // Check Address
                            $checkAddress = userAddress::where('id',$allObject[0]['address_id'])
                            ->where('user_id',$request->userID)
                            ->whereNotNull('id')
                            ->exists();



                            if(!$checkAddress)
                                return (new ResponseHandler)->sendErrorResponse($this->const['SELECT_ADDRESS'], 400);


                                    $totalWeight=0;

                                    foreach ($allObject[1] as $key => $value) {

                                        if ($allObject[1][$key]['pieces'] == 0)
                                            return (new ResponseHandler)->sendErrorResponse($this->const['SELECT_QUANTITY'], 200);

                                        // for Checking individual product weight
                                        if($allObject[1][$key]['product_weight'] * $allObject[1][$key]['pieces'] > $this->const['WEIGHT_LIMIT'])
                                            return (new ResponseHandler)->sendErrorResponse($this->const['WEIGHT_ERROR_MSG'], 400);

                                        // For calculating total Product Weight
                                        $totalWeight += $allObject[1][$key]['product_weight'] * $allObject[1][$key]['pieces'];

                                    }


                                    // calculating total price and update pieces
                                    $totalPrice = 0;

                                    $calculateGST = 0;

                                    $deliveryCharge = 0;

                                    foreach ($allObject[1] as $key => $value) {

                                        addtoCart::where('id', $allObject[1][$key]['cart_id'])->update([
                                            'pieces' => $allObject[1][$key]['pieces']
                                        ]);

                                        $calculateGST += ( $allObject[1][$key]['price'] * $allObject[1][$key]['pieces'] * ($allObject[1][$key]['gst'] / 100) );

                                        $totalPrice += $allObject[1][$key]['price'] ;

                                        $deliveryCharge += $allObject[1][$key]['deliveryCharge'];

                                    }


                                    $pieces = $allObject[1][$key]['pieces'];
                                    $receiptId = Str::random(20);
                                    $user_id = $request->userID;
                                    $orderDate = date('Y-m-d');
                                    $api = new Api($this->const["RAZOR_PAY_API_KEY"], $this->const["RAZOR_PAY_SECRET"]);

                                    // creating order
                                    $order = $api->order->create(array(
                                        'receipt' => $receiptId, 'amount' => ($totalPrice + $calculateGST + $deliveryCharge) * 100, 'currency' => 'INR'
                                    ));

                                    // helper function generating Shyamoni Random Order ID
                                    $shyamoni_order_id = shyamoni_order_id(15);

                                    $response = [
                                        'order_id' => $order['id'],
                                        'amount' => $totalPrice * 100,
                                        'shyamoni_order_id' => $shyamoni_order_id,
                                    ];

                                    $storeOrderDetails =  DB::table('order_tables')->insert([
                                        'totalPrice' => $totalPrice,
                                        'razorpay_order_id' => $order['id'],
                                        'shyamoni_order_id' => $shyamoni_order_id,
                                        'receiptId' => $receiptId,
                                        'user_id' => $user_id,
                                        'orderDate' => $orderDate,
                                        'totalPrice' => $totalPrice,
                                        'product_weight'=> $totalWeight,
                                        'paymentDone' => 'pending',
                                    ]);


                                    foreach ($allObject[1] as $key => $value) {

                                        addtoCart::where('id', $allObject[1][$key]['cart_id'])->update([
                                            'orderID' => $order['id'],
                                            'shyamoni_order_id' => $shyamoni_order_id,
                                        ]);

                                        // Insert Pieces in StockOut Field
                                        DB::table('stock_tables')->insert([
                                            'product_id'=> $allObject[1][$key]['product'],
                                            'product_weight'=> $allObject[1][$key]['product_weight_id'],
                                            'stock_out' => $allObject[1][$key]['pieces'],
                                            'stock_status' => 3,
                                            'status' => 1,
                                        ]);

                                    }

                                    $getUserAddress = userAddress::join('statemaster','statemaster.id','=','user_addresses.state')
                                    ->join('citymaster','citymaster.id','=','user_addresses.city')
                                    ->select(DB::raw('CONCAT(user_addresses.address_line_1,",",user_addresses.address_line_2,",",citymaster.city_name,",",district,",",statemaster.state_name,",",country,",",zip) AS address'))
                                    ->where('user_id',$request->userID)
                                    ->where('status',1)
                                    ->get();


                                    orderTable::where('shyamoni_order_id',$shyamoni_order_id)->update([
                                        'address_id'=>$getUserAddress[0]['address'],
                                        'razorpay_paymentID'=>$request->razorpay_payment_id,
                                    ]);

                                    $getUserPrefillDetails = User::where('id', $request->userID)->select('name', 'email', 'phoneNo')->first();

                                    if ($storeOrderDetails)
                                        return (new ResponseHandler)->sendSuccessResponse(['orderResponse' => $response, 'getUserPrefillDetails' => $getUserPrefillDetails], 200);



        } catch (\Exception $e) {
            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function paymentSuccess(Request $request)
    {

        try {

            // if payment is cancelled
            if($request->cancel == 'cancel'){

                orderTable::where('razorpay_order_id', $request->all()['razorpay_order_id'])
                ->where('user_id',$request->userID)
                ->update([
                    'paymentDone' => 'cancelled',
                ]);

                orderTable::where('razorpay_order_id',$request->all()['razorpay_order_id'])
                ->delete();

                addtoCart::where('shyamoni_order_id',$request->shyamoni_order_id)
                ->where('user_id',$request->userID)
                ->update([
                    'shyamoni_order_id' => NULL,
                    'orderID'=>NULL
                ]);

                return (new ResponseHandler)->sendSuccessResponse($this->const['PAYMENT_CANCELLED'], 200);

            }else
            {
                    $signatureStatus = $this->SignatureVerify(
                        $request->all()['razorpay_signature'],
                        $request->all()['razorpay_payment_id'],
                        $request->all()['razorpay_order_id']
                    );


                    if ($signatureStatus == true) {

                        orderTable::where('razorpay_order_id', $request->all()['razorpay_order_id'])
                        ->where('user_id',$request->userID)
                        ->update([
                            'paymentDone' => 'captured',
                            'razorpay_paymentID' => $request->all()['razorpay_payment_id'],
                        ]);

                        // if payment captured
                            $checkPaymentStatus = DB::table('order_tables')
                            ->where('razorpay_order_id',$request->razorpay_order_id)
                            ->where('razorpay_paymentID',$request->razorpay_payment_id)
                            ->where('user_id',$request->userID)
                            ->get();


                            if($checkPaymentStatus[0]->razorpay_paymentID == $request->razorpay_payment_id){

                                    if($checkPaymentStatus[0]->paymentDone == 'captured'){

                                        if(explode(",",$checkPaymentStatus[0]->address_id)[2] != env("city")){

                                                $orderErrResponse = (new DeliveryVendorController)->createOrder($checkPaymentStatus[0]->shyamoni_order_id,$request);

                                                if(is_null($orderErrResponse)){

                                                    $fetchMsg = MailMessage::where('constant',env("createOrder_constant"))->first();

                                                    if($fetchMsg){

                                                        $CreateOrderMailJob =  CreateOrderMailJob::dispatch((new MailController)->createOrderMail($request,$fetchMsg,$checkPaymentStatus[0]->shyamoni_order_id));

                                                        if($CreateOrderMailJob){

                                                            return (new ResponseHandler)->sendSuccessResponse($this->const['WEB_HOOK_PAYMENT_CAPTURED'], 200);

                                                        }

                                                    }

                                                }
                                                else
                                                {

                                                    addtoCart::where('shyamoni_order_id', $checkPaymentStatus[0]->shyamoni_order_id)
                                                    ->where('user_id',$request->userID)
                                                    ->update([
                                                        'shyamoni_order_id' => NULL,
                                                        'orderID'=>NULL
                                                    ]);

                                                    return (new ResponseHandler)->sendErrorResponse($this->const['ORDER_FAILED'], 503);
                                                }


                                        }
                                        else
                                        {

                                                $fetchMsg = MailMessage::where('constant',env("createOrder_constant"))
                                                ->first();

                                                (new DeliveryVendorController)->createLocalOrder($checkPaymentStatus[0]->shyamoni_order_id);

                                                CreateOrderMailJob::dispatch((new MailController)->createOrderMail($request,$fetchMsg,$checkPaymentStatus[0]->shyamoni_order_id));

                                                return (new ResponseHandler)->sendSuccessResponse($this->const['WEB_HOOK_PAYMENT_CAPTURED'], 200);

                                        }

                                    }
                                    else{
                                        return (new ResponseHandler)->sendErrorResponse($this->const['WEB_HOOK_PAYMENT_FAIL'], 503);
                                    }

                            }


                    }else
                    {

                            // if Payment is failed
                            orderTable::where('razorpay_order_id', $request->razorpay_order_id)
                            ->update([
                                'paymentDone' => "failed",
                            ]);

                            addtoCart::where('shyamoni_order_id', $request->shyamoni_order_id)
                            ->where('user_id',$request->userID)
                            ->update([
                                'shyamoni_order_id' => NULL,
                                'orderID'=>NULL
                            ]);


                            $checkPaymentStatus = DB::table('order_tables')
                            ->where('razorpay_order_id',$request->razorpay_order_id)
                            ->where('razorpay_paymentID',$request->razorpay_payment_id)
                            ->where('user_id',$request->userID)
                            ->get();


                            if($checkPaymentStatus[0]->paymentDone == 'failed'){
                                return (new ResponseHandler)->sendErrorResponse($this->const['WEB_HOOK_PAYMENT_FAIL'], 503);
                            }

                            // return (new ResponseHandler)->sendErrorResponse($this->const['PAYMENT_FAILURE'], 503);


                    }


            }

        } catch (\Exception $e) {
            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    private function SignatureVerify($_signature, $_paymentId, $_orderId)
    {
        try {

            $generated_signature = hash_hmac('sha256',$_orderId . "|" . $_paymentId, $this->const["RAZOR_PAY_SECRET"]);

            return ($generated_signature == $_signature) ? true : false;

        } catch (SignatureVerificationError $e) {

            // If Signature is not correct its give a excetption so we use try catch
            return false;
        }
    }

    public function paymentVerification(Request $request){

        try {

            $paymentResponse = $request->getContent();

            $event = json_decode($request->getContent(),true)['event'];

            switch ($event) {

                case 'payment.captured':

                        $paymentResponse = DB::table('paymentresponse')
                        ->insertGetId([
                            'razorpay_order_id'=> json_decode($request->getContent(),true)['payload']['payment']['entity']['order_id'],
                            'razorpay_payment_id'=> json_decode($request->getContent(),true)['payload']['payment']['entity']['id'],
                            'paymentResponse'=>$paymentResponse,
                            'paymentDone' => 'captured'
                        ]);

                        DB::table('order_tables')->where('razorpay_order_id',json_decode($request->getContent(),true)['payload']['payment']['entity']['order_id'])
                        ->update([
                            'paymentResponse' => $paymentResponse,
                            'razorpay_paymentID'=> json_decode($request->getContent(),true)['payload']['payment']['entity']['id'],
                        ]);

                        break;

                case 'payment.failed':


                            $paymentResponse = DB::table('paymentresponse')
                            ->insertGetId([
                                'razorpay_order_id'=> json_decode($request->getContent(),true)['payload']['payment']['entity']['order_id'],
                                'razorpay_payment_id'=> json_decode($request->getContent(),true)['payload']['payment']['entity']['id'],
                                'paymentResponse'=>$paymentResponse,
                                'paymentDone' => 'failed'
                            ]);

                            DB::table('order_tables')->where('razorpay_order_id',json_decode($request->getContent(),true)['payload']['payment']['entity']['order_id'])
                            ->update([
                                'paymentResponse' => $paymentResponse,
                                'razorpay_paymentID'=> json_decode($request->getContent(),true)['payload']['payment']['entity']['id'],
                            ]);


                            break;


                case 'refund.processed':


                            $paymentResponse = DB::table('paymentresponse')
                            ->insertGetId([
                                'razorpay_order_id'=> json_decode($request->getContent(),true)['payload']['payment']['entity']['order_id'],
                                'razorpay_payment_id'=> json_decode($request->getContent(),true)['payload']['payment']['entity']['id'],
                                'paymentResponse'=>$paymentResponse,
                                'paymentDone' => 'refund processed'
                            ]);

                            DB::table('order_tables')->where('razorpay_order_id',json_decode($request->getContent(),true)['payload']['payment']['entity']['order_id'])
                            ->update([
                                'paymentResponse' => $paymentResponse,
                                'razorpay_paymentID'=> json_decode($request->getContent(),true)['payload']['payment']['entity']['id'],
                            ]);


                        break;

                case 'refund.failed':


                        $paymentResponse = DB::table('paymentresponse')
                        ->insertGetId([
                            'razorpay_order_id'=> json_decode($request->getContent(),true)['payload']['payment']['entity']['order_id'],
                            'razorpay_payment_id'=> json_decode($request->getContent(),true)['payload']['payment']['entity']['id'],
                            'paymentResponse'=>$paymentResponse,
                            'paymentDone' => 'refund failed'
                        ]);

                        DB::table('order_tables')->where('razorpay_order_id',json_decode($request->getContent(),true)['payload']['payment']['entity']['order_id'])
                        ->update([
                            'paymentResponse' => $paymentResponse,
                            'razorpay_paymentID'=> json_decode($request->getContent(),true)['payload']['payment']['entity']['id'],
                        ]);


                        break;


                case 'refund.created':

                        $paymentResponse = DB::table('paymentresponse')
                        ->insertGetId([
                            'razorpay_order_id'=> json_decode($request->getContent(),true)['payload']['payment']['entity']['order_id'],
                            'razorpay_payment_id'=> json_decode($request->getContent(),true)['payload']['payment']['entity']['id'],
                            'paymentResponse'=>$paymentResponse,
                            'paymentDone' => 'refund created'
                        ]);

                        DB::table('order_tables')->where('razorpay_order_id',json_decode($request->getContent(),true)['payload']['payment']['entity']['order_id'])
                        ->update([
                            'paymentResponse' => $paymentResponse,
                            'razorpay_paymentID'=> json_decode($request->getContent(),true)['payload']['payment']['entity']['id'],
                        ]);


                        break;

                default:

                        return;

                    break;
            }


        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();

        }

    }

    public function getPaymentStatus(Request $request){

        try{

            $checkPaymentStatus = DB::table('order_tables')
            ->where('razorpay_order_id',$request->razorpay_order_id)
            ->where('razorpay_paymentID',$request->razorpay_payment_id)
            ->where('user_id',$request->userID)
            ->get();


            if($checkPaymentStatus[0]->razorpay_paymentID == $request->razorpay_payment_id){

                    if($checkPaymentStatus[0]->paymentDone == 'captured'){

                        if(explode(",",$checkPaymentStatus[0]->address_id)[2] != env("city")){

                            (new DeliveryVendorController)->createOrder($checkPaymentStatus[0]->shyamoni_order_id,$request);


                                $fetchMsg = MailMessage::where('constant',env("createOrder_constant"))->first();

                                if($fetchMsg){

                                    $CreateOrderMailJob =  CreateOrderMailJob::dispatch((new MailController)->createOrderMail($request,$fetchMsg,$checkPaymentStatus[0]->shyamoni_order_id));

                                    if($CreateOrderMailJob){

                                        return (new ResponseHandler)->sendSuccessResponse($this->const['WEB_HOOK_PAYMENT_CAPTURED'], 200);

                                    }

                                }

                        }
                        else
                        {

                                $fetchMsg = MailMessage::where('constant',env("createOrder_constant"))
                                ->first();

                                (new DeliveryVendorController)->createLocalOrder($checkPaymentStatus[0]->shyamoni_order_id);

                                CreateOrderMailJob::dispatch((new MailController)->createOrderMail($request,$fetchMsg,$checkPaymentStatus[0]->shyamoni_order_id));

                                return (new ResponseHandler)->sendSuccessResponse($this->const['WEB_HOOK_PAYMENT_CAPTURED'], 200);

                        }



                }
                elseif($checkPaymentStatus[0]->paymentDone == 'failed'){
                    return (new ResponseHandler)->sendErrorResponse($this->const['WEB_HOOK_PAYMENT_FAIL'], 503);
                }

            }

        }catch (\Exception $e) {
            dd($e);
            return (new ResponseHandler)->sendErrorResponse();

        }

    }

    public function reloadApi(Request $request){

        try {


                $getPaymentID = orderTable::select('razorpay_order_id')
                ->where('user_id',$request->userID)
                ->where('razorpay_paymentID','=',NULL)
                ->first();


                if($getPaymentID){

                        $nullOrderID = DB::table('addto_carts')
                        ->where('user_id',$request->userID)
                        ->where('orderID',$getPaymentID->razorpay_order_id)
                        ->where('deleted_at','=',NULL)
                        ->update([
                            'shyamoni_order_id'=>NULL,
                            'orderID'=>NULL
                        ]);

                        if($nullOrderID){
                            orderTable::where('razorpay_order_id',$getPaymentID->razorpay_order_id)
                           ->delete();
                        }
                }



        }catch (\Exception $e) {
            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }

    }

    public function InvoicePage(Request $request, $shyamoni_order_id)
    {

        $getPriceDetails = DB::table('addto_carts')
            ->join('product_table', 'product_table.id', '=', 'addto_carts.product_id')
            ->join('product_weight', 'product_weight.id', '=', 'addto_carts.product_weight')
            ->where('user_id', $request->userID)
            ->where('shyamoni_order_id', $shyamoni_order_id)
            ->where('shyamoni_order_id', '!=', null)
            ->select('pieces as quantity', DB::raw('(originalPrice-discountAmount) as UnitPrice'),
                DB::raw('((originalPrice-discountAmount)*pieces) as totalPrice'), 'product_table.productName', 'product_table.shortDesc','discountAmount as discount_price', 'originalPrice', 'deliveryCharge', 'gst')
            ->get();

        $getAddressDetails = orderTable::where('shyamoni_order_id', $shyamoni_order_id)
            ->join('users', 'users.id', '=', 'order_tables.user_id')
            ->where('order_tables.user_id', auth('sanctum')->user()->id)
            ->select('address_id', 'users.email', 'users.phoneNo', 'users.name', 'receiptId', 'shyamoni_order_id')
            ->first();


        $customer = new Buyer([
            'name' => $getAddressDetails->name,
            'address' => $getAddressDetails->address_id,
            'phone' => $getAddressDetails->phoneNo,
            'custom_fields' => [
                'email' => $getAddressDetails->email,
                'Invoice Id' => $getAddressDetails->receiptId,
                'Order Id' => $getAddressDetails->shyamoni_order_id,
            ],
        ]);

        $discountPrice = 0;
        $items = [];

        $totalDeliveryCharge = 0;

        foreach ($getPriceDetails as $key => $value) {


            $discountPrice = $getPriceDetails[$key]->discount_price * $getPriceDetails[$key]->quantity;

            array_push($items, (new InvoiceItem())->title($getPriceDetails[$key]->productName)
                    ->description($getPriceDetails[$key]->shortDesc)
                    ->pricePerUnit($getPriceDetails[$key]->originalPrice)
                    ->quantity($getPriceDetails[$key]->quantity)
                    ->discount($discountPrice));

            $totalDeliveryCharge += $getPriceDetails[$key]->deliveryCharge * $getPriceDetails[$key]->quantity;

        }

        $invoice = Invoice::make()
            ->buyer($customer)
            ->addItems($items)
            ->logo('https://www.shyamoni.com/images/logo/invoice_logo.png')
            ->taxRate($getPriceDetails[$key]->gst)
            ->status(__('invoices::invoice.paid'))
            ->shipping($totalDeliveryCharge);

        return $invoice->download();

    }

}
