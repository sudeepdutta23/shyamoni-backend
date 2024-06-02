<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\{
    Constants,
    User,
};
use App\Http\Requests\{
    Login,
    SignUp,
    ForgotPassword,
    OTPVerify,
    UpdatePassword,
    contactUs
};
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Http\Utils\{
    Authorize,
    ResponseHandler
};

use Exception;
use Illuminate\Support\Facades\File;




use App\Jobs\TestMail;



class MailController extends Controller
{


    private $const;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    public function userRegisterMail($request,$fetchMsg){

        $body = $fetchMsg->message_body;

        $subject = $fetchMsg->message_subject;

        $string = str_replace('{{name}}',$request->name,$body);

        // $mailSent = Mail::send('mail_template.createOrder', ['getPriceDetails'=>$getPriceDetails,'shyamoniOrderID'=>$shyamoni_order_id,'TotalPrice'=>$TotalPrice], function ($message) use ($request, $subject) {


        // $mailSent = Mail::send([], [], function ($message) use ($subject,$request,$string) {
        $mailSent = Mail::send('mail_template.registerSuccess', ['name'=>$request->name], function ($message) use ($subject,$request,$string){
            $message->to($request->email)
                ->subject($subject)
                ->from($this->const['MAIL_USERNAME'], '')
                ->html($string, 'text/html');
        });

        if ($mailSent)
            return true;
    }

    public function passwordResetMail($randomOTP,$request,$fetchMsg,$digits){

        $subject = $fetchMsg->message_subject;

        $message_body = $fetchMsg->message_body;

        $string = str_replace('{{digits}}', $digits, $message_body);

        $mailSent = Mail::send('mail_template.passwordResetMail',['otp'=>$digits,'name'=>$request->name],function ($message) use ($request,$string,$subject) {
            $message->to($request->email)
                ->subject($subject)
                ->from($this->const['MAIL_USERNAME'], '')
                ->html($string,'text/html');
        });

        if ($mailSent)
            return true;

    }

    public function passwordUpdate($fetchMsg,$request){


        $subject = $fetchMsg->message_subject;

        $message_body = $fetchMsg->message_body;

        $mailSent = Mail::send('mail_template.passwordUpdate',['name'=>$request->name], function ($message) use ($message_body,$subject,$request) {
            $message->to($request->email)
                ->subject($subject)
                ->from($this->const['MAIL_USERNAME'], '')
                ->html($message_body,'text/html');
        });

        if ($mailSent)
            return true;


    }

    public function contactMail($fetchMsg,$request){

        $subject = $fetchMsg->message_subject;

        $message_body = $fetchMsg->message_body;

        $variables = ["{{email}}", "{{comment}}"];

        $requestContent   = [$request->email,$request->comment];

        $string = str_replace($variables,$requestContent,$message_body);

        $mailSent = Mail::send([], [], function ($message) use ($subject,$string) {
            $message->to($this->const['MAIL_USERNAME'])
                ->subject($subject)
                ->from($this->const['MAIL_USERNAME'])
                ->html($string, 'text/html');
          });

        if ($mailSent)
            return true;
    }

    public function createOrderMail($request,$fetchMsg,$shyamoni_order_id){


        $subject = $fetchMsg->message_subject;

            $orderDetails = DB::table('order_tables')
            ->where('order_tables.shyamoni_order_id',$shyamoni_order_id)
            ->where('user_id',$request->userID)
            ->select('totalPrice','shyamoni_order_id','receiptId')
            ->first();


            // $getPriceDetails = DB::table('addto_carts')
            // ->join('product_table','product_table.id','=','addto_carts.product_id')
            // ->join('product_weight','product_weight.id','=','addto_carts.product_weight')
            // ->where('user_id',$request->userID)
            // ->where('shyamoni_order_id',$shyamoni_order_id)
            // ->where('shyamoni_order_id','!=',NULL)
            // ->select('pieces as quantity',DB::raw('(originalPrice-discountAmount) as UnitPrice'),
            // DB::raw('((originalPrice-discountAmount)*pieces) as totalPrice'),'product_table.productName')
            // ->get();



            $getPriceDetails = DB::table('addto_carts')
            ->join('product_table','product_table.id','=','addto_carts.product_id')
            ->join('product_weight','product_weight.id','=','addto_carts.product_weight')
            ->where('user_id',$request->userID)
            ->where('shyamoni_order_id',$shyamoni_order_id)
            ->where('shyamoni_order_id','!=',NULL)
            ->select('pieces','discountAmount','specialPrice','gst','deliveryCharge',DB::raw('(originalPrice-discountAmount) as UnitPrice'),
            DB::raw('((originalPrice-discountAmount)*pieces) as totalPrice'),'product_table.productName')
            ->get();


            $calculateDiscount = 0;
            $calculateGST = 0;
            $deliveryCharge = 0;

            foreach ($getPriceDetails as $key => $value) {

                $calculateDiscount += $getPriceDetails[$key]->pieces * $getPriceDetails[$key]->discountAmount;

                $calculateGST +=  $getPriceDetails[$key]->specialPrice * $getPriceDetails[$key]->pieces * ($getPriceDetails[$key]->gst/100);

                $deliveryCharge += $getPriceDetails[$key]->deliveryCharge;
            }

            $totalPrice = $orderDetails->totalPrice + $calculateGST + $deliveryCharge;


        $mailSent = Mail::send('mail_template.createOrder', ['getPriceDetails'=>$getPriceDetails,'shyamoniOrderID'=>$shyamoni_order_id,'totalPrice'=>$totalPrice,'orderDetails'=>$orderDetails], function ($message) use ($request, $subject) {

            $emails = [$request->user_email_id,$this->const['MAIL_USERNAME']];

            $message->to($emails)
                ->subject($subject)
                ->from($this->const['MAIL_USERNAME']);

        });

        if($mailSent){
            return true;
        }


    }

    public function cancelOrderMail($fetchMsg,$request,$shyamoniOrderID){


        $subject = $fetchMsg->message_subject;

        // $TotalPrice = DB::table('order_tables')
        // ->where('order_tables.shyamoni_order_id',$shyamoniOrderID)
        // ->where('user_id',$request->userID)
        // ->select('totalPrice','shyamoni_order_id','receiptId')->first();


        $orderDetails = DB::table('order_tables')
        ->where('order_tables.shyamoni_order_id',$shyamoniOrderID)
        ->where('user_id',$request->userID)
        ->select('totalPrice','shyamoni_order_id','receiptId')
        ->first();


        $getPriceDetails = DB::table('addto_carts')
        ->join('product_table','product_table.id','=','addto_carts.product_id')
        ->join('product_weight','product_weight.id','=','addto_carts.product_weight')
        ->where('user_id',$request->userID)
        ->where('shyamoni_order_id',$shyamoniOrderID)
        ->where('shyamoni_order_id','!=',NULL)
        ->select('pieces','discountAmount','specialPrice','gst','deliveryCharge',DB::raw('(originalPrice-discountAmount) as UnitPrice'),
        DB::raw('((originalPrice-discountAmount)*pieces) as totalPrice'),'product_table.productName')
        ->get();


        $calculateDiscount = 0;
        $calculateGST = 0;
        $deliveryCharge = 0;

        foreach ($getPriceDetails as $key => $value) {

            $calculateDiscount += $getPriceDetails[$key]->pieces * $getPriceDetails[$key]->discountAmount;

            $calculateGST +=  $getPriceDetails[$key]->specialPrice * $getPriceDetails[$key]->pieces * ($getPriceDetails[$key]->gst/100);

            $deliveryCharge += $getPriceDetails[$key]->deliveryCharge;
        }

        $totalPrice = $orderDetails->totalPrice + $calculateGST + $deliveryCharge;


        $mailSent = Mail::send('mail_template.cancelOrder', ['getPriceDetails'=>$getPriceDetails,'shyamoniOrderID'=>$shyamoniOrderID,'totalPrice'=>$totalPrice,'orderDetails'=>$orderDetails], function ($message) use ($request, $subject) {

            $emails = [$request->user_email_id,$this->const['MAIL_USERNAME']];
            $message->to($emails)
                ->subject($subject)
                ->from($this->const['MAIL_USERNAME']);
        });

        if ($mailSent)
            return true;

    }

    public function OrderErrorMail($request,$fetchMsg,$shyamoniOrderID,$errMsg,$errSubject){


            $subject = $fetchMsg->message_subject;

            $message_body = $fetchMsg->message_body;

            $string = str_replace('{{randomOrderID}}',$shyamoniOrderID,$message_body);

            $mailSent = Mail::send('mail_template.orderFailedMail', ['name'=>$request->name,'shyamoniOrderID'=>$shyamoniOrderID], function ($message) use ($subject,$string,$request) {

            $emails = [$request->user_email_id,$this->const['MAIL_USERNAME']];

            $message->to($emails)
                ->subject($subject)
                ->from($this->const['MAIL_USERNAME'])
                ->html($string, 'text/html');
          });


          $mailSent = Mail::send([], ['name'=>$request->name,'shyamoniOrderID'=>$shyamoniOrderID], function ($message) use ($errMsg,$errSubject) {

            $emails = ['lfree4857@gmail.com'];

            $message->to($emails)
                ->subject($errSubject)
                ->from($this->const['MAIL_USERNAME'])
                ->html($errMsg, 'text/html');
          });


        if ($mailSent)
            return true;


    }


}
