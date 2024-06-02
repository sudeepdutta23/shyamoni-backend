<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\{
    Constants,
    MailMessage,
    logConstants
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
use Carbon\Carbon;
use App\Http\Utils\{
    Authorize,
    ResponseHandler
};

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class storeMessageController extends Controller
{


    private $const,$logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();

        $this->logConst = (new logConstants)->getLogConstants();

    }


    public function addMailMessage(Request $request){


        try {

                    // $logMsg='Mail Message Added';

                    $storeMailMessage = MailMessage::create([
                        'message_subject' => $request->message_subject,
                        'message_body' => $request->message_body,
                        'constant' => $request->constant,
                    ]);

                    if($storeMailMessage){
                        // addToLog($logMsg,25);
                        return (new ResponseHandler)->sendSuccessResponse($this->const['STORE_MAIL_MESSAGE'], 200);
                    }

        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }

    }


    public function editMailMessage(Request $request,$id){


        try {


                    // $logMsg='Mail Message Updated';

                    $editMailMessage = DB::table('mail_messages')->where('id',$id)->update([
                        'message_subject' => $request->message_subject,
                        'message_body' => $request->message_body,
                        'constant' => $request->constant,
                    ]);

                    if($editMailMessage){
                        // addToLog($logMsg,26);
                        return (new ResponseHandler)->sendSuccessResponse($this->const['EDIT_MAIL_MESSAGE'], 200);
                    }

        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }

    }


    public function getAllMailMessage(Request $request,$id){


        try {

                    $getAllMailMessage = MailMessage::orderBy('id','desc')->get();

                    if($getAllMailMessage){
                        return (new ResponseHandler)->sendSuccessResponse($getAllMailMessage, 200);
                    }

        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }

    }

    public function deleteMailMessage(Request $request,$id){

        // $dateTime= date('d-m-Y h:i:s');

        // $logMsg = str_replace(['{{username}}','{{time}}'], [Auth::user()->name,$dateTime], $this->logConst['MAIL_MSG_DELETED_BY']);

        // $logMsg='Mail Message Deleted';

        $deleteMailMessage = MailMessage::where('id', $id)->delete();

        if($deleteMailMessage){
            // addToLog($logMsg,27);
            return (new ResponseHandler)->sendSuccessResponse($this->const['DELETE_MAIL_MESSAGE'], 200);
        }

    }





}
