<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\MailController;
use Illuminate\Support\Facades\Auth;
use App\Models\{
    Constants,
    logConstants,
    User,
    MailMessage,
    ActivityLogModel,
    userAddress,
};

use Laravel\Sanctum\PersonalAccessToken;

use App\Http\Requests\{
    Login,
    SignUp,
    ForgotPassword,
    OTPVerify,
    UpdatePassword,
    contactUs
};

use App\Jobs\{
    userRegisterMailJob,
    passwordResetMailJob,
    contactMailJob,
    passwordUpdateJob
};


use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Utils\{
    ResponseHandler
};

use App\Http\Requests\{
    updateProfile
};

use Exception;
use Illuminate\Support\Facades\File;

class LoginRegisterController extends Controller
{
    private $const,$logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();

        $this->logConst = (new logConstants)->getLogConstants();

    }

    public function register(SignUp $request)
    {
        try {

            $role_id = (isset($request->role_id)) ? (int) $request->role_id : 2;
            $checkUser = User::where(['email' => $request->email, 'phoneNo' => $request->phoneNo])->select('id')->first();
            if ($checkUser) {
                $token = $checkUser->createToken('authToken')->plainTextToken;
                if ($token)
                    return (new ResponseHandler)->sendSuccessResponse(["token" => $token], 200);
            }

            $createNewUser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phoneNo' => $request->phoneNo,
                'role_id' => $role_id,
                'password' => Hash::make($request->password),
            ]);

            if ($createNewUser) {

                    $fetchMsg = MailMessage::where('constant',env("user_register_constant"))
                    ->first();

                    userRegisterMailJob::dispatch((new MailController)->userRegisterMail($request,$fetchMsg));

                    $user = User::where('email', $request->email)->orWhere('phoneNo', $request->phoneNo)->select('id')->first();

                    $token = $user->createToken('authToken')->plainTextToken;

                    if ($token)
                        return (new ResponseHandler)->sendSuccessResponse(["token" => $token], 200);
            }

        } catch (\Exception $e) {
            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function login(Login $request)
    {

        try {

            $user = User::where('email', $request->username)
            ->orWhere('phoneNo', $request->username)
            ->first();

            if(!empty($user)){


                $admin_role = explode(",",env('ADMIN_ROLE'));

                $user_role = explode(",",env('USER_ROLE'));

                if (!$user || !Hash::check($request->password, $user->password))
                return (new ResponseHandler)->sendErrorResponse($this->const['UNAUTHORIZED_CREDENTIALS'], 400);

                if($request->getPathInfo()=="/api/v1/admin/login" && !in_array($user->role_id,$admin_role))
                    return (new ResponseHandler)->sendErrorResponse($this->const['UNAUTHORIZED_CREDENTIALS'], 400);

                if($request->getPathInfo()=="/api/v1/user/login" && !in_array($user->role_id,$user_role))
                    return (new ResponseHandler)->sendErrorResponse($this->const['UNAUTHORIZED_CREDENTIALS'], 400);

                if (!$user || !Hash::check($request->password, $user->password))
                    return (new ResponseHandler)->sendErrorResponse($this->const['UNAUTHORIZED_CREDENTIALS'], 400);

                $token = $user->createToken('authToken')->plainTextToken;

                if ($token)

                    return (new ResponseHandler)->sendSuccessResponse(["token" => $token], 200);


            }else
            {

                return (new ResponseHandler)->sendErrorResponse($this->const['UNAUTHORIZED_CREDENTIALS'], 400);


            }

        } catch (\Exception $e) {
            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function forgotPassword(ForgotPassword $request)
    {
        try {

            $checkMail = User::where('email', $request->email)->first();

            if (!$checkMail)
                return (new ResponseHandler)->sendErrorResponse($this->const['USER_NOT_EXIST'], 200);

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => Str::random(64),
                'created_at' => Carbon::now()
            ]);

            // Helper Function for Random Otp Number
            $digits = randomNumber();

            $randomOTP = 'Your OTP is' . ' ' . $digits;

            $fetchMsg = MailMessage::where('constant',env("forget_password_constant"))
            ->first();

            passwordResetMailJob::dispatch((new MailController)->passwordResetMail($randomOTP,$request,$fetchMsg,$digits));

            User::where('email', $request->email)->update([
                'userOTP' => $digits,
            ]);

            return (new ResponseHandler)->sendSuccessResponse($this->const['PASSWORD_MAIL_SENT'], 200);

        } catch (\Exception $e) {
            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function sendContactMail(contactUs $request){

        try {


            $fetchMsg = MailMessage::where('constant',env("send_ContactMail_constant"))
            ->first();

            DB::table('contact_us')->insert([
                'name' => $request->name,
                'email'=> $request->email,
                'comment'=> $request->comment
            ]);

            contactMailJob::dispatch((new MailController)->contactMail($fetchMsg,$request));

            return (new ResponseHandler)->sendSuccessResponse($this->const['CONTACT_US_MAIL'], 200);


        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }

    }

    public function updatePassword(UpdatePassword $request)
    {
        try {

            $checkMail = User::where('email', $request->email)->where('userOTP',$request->userOTP)->first();

            if (!$checkMail){

                return (new ResponseHandler)->sendErrorResponse($this->const['UNAUTHORIZED_USER'], 401);

            }
            else
            {
                $fetchMsg = MailMessage::where('constant',env("password_update_constant"))
                ->first();

                $updatePassword = User::where('email', $request->email)
                ->update([
                    'password' => Hash::make($request->password),
                    'userOTP' => NULL
                ]);

                if($updatePassword)

                    passwordUpdateJob::dispatch((new MailController)->passwordUpdate($fetchMsg,$request));

                    return (new ResponseHandler)->sendSuccessResponse($this->const['UPDATE_PASSWORD'], 200);


            }


        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function verifyOtp(OTPVerify $request)
    {
        try {
            $verifyOtp = User::where('email', $request->email)->where('userOTP', $request->userOTP)->first();
            return (new ResponseHandler)->sendSuccessResponse(!$verifyOtp ? false : true);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }


    public function adminLogout(Request $request)
    {
        try {

            auth('sanctum')->user()->tokens()->delete();

                return (new ResponseHandler)->sendSuccessResponse($this->const['LOG_OUT'], 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function userLogout(Request $request)
    {
        try {
            $tockenID = auth('sanctum')->user()->id;
            $deleteTocken = DB::table('personal_access_tokens')->where('tokenable_id', $tockenID)->delete();
            if ($deleteTocken)
                return (new ResponseHandler)->sendSuccessResponse($this->const['LOG_OUT'], 200);
        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function updateProfileInfo(updateProfile $request)
    {
        try {


                $userPhotoFile = $request->file('image');
                $userPhoto =  str_replace(' ', '', $userPhotoFile->getClientOriginalName());
                $userPhotoFile->move(public_path(env('USER_PHOTO_PATH').'/'.$request->name), $userPhoto);
                $userPhotoPath = asset(env('USER_PHOTO_PATH').'/'.$request->name).'/'.$userPhoto;
                $checkImage = User::where('id',$request->userID)->first();

                if($userPhotoPath != $checkImage->userPhoto){

                    $updateProfileInfo = User::where('id', $request->userID)->update([
                        'gender' => $request->gender,
                        'dob' => $request->dob,
                        'userPhoto' => $userPhotoPath ? $userPhotoPath : $checkImage->userPhoto
                    ]);

                    if(File::exists(public_path(substr($checkImage->userPhoto,22))))
                    {
                        $deleteFile = public_path(substr($checkImage->userPhoto,22));
                        File::delete($deleteFile);
                    }
                }else
                {
                        $updateProfileInfo = User::where('id', $request->userID)->update([
                            'gender' => $request->gender,
                            'dob' => $request->dob,
                        ]);

                }

            if ($updateProfileInfo)
                return (new ResponseHandler)->sendSuccessResponse($this->const['PROFILE_UPDATE'], 200);
        } catch (\Exception $e) {

            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function checkAdmin(Request $request)
    {
        try {


            [$id, $token] = explode('|', $request->header('Authorization'), 2);

            $hased_token = hash('sha256', $token);

            $token = PersonalAccessToken::where('token',$hased_token)->first();

            $fetch_user = User::where('id',$token->tokenable_id)->first();

            $admin_role = explode(",",env('ADMIN_ROLE'));

            return (new ResponseHandler)->sendSuccessResponse(in_array($fetch_user->role_id,$admin_role) ? true : false);

        } catch (Exception $e) {

            return response()->json([
                "error" => true,
                "data" => 'Unauthenticated',
                "status_code"=> 1000
            ])->setStatusCode(401);

        }
    }

    public function checkUser(Request $request)
    {

            [$id, $token] = explode('|', $request->Authorization, 2);

            $hased_token = hash('sha256', $token);

            $token = PersonalAccessToken::where('token',$hased_token)->first();

            if(!empty($token->tokenable_id)){

                $fetch_user = User::where('id',$token->tokenable_id)->first();

                $user_role = explode(",",env('USER_ROLE'));

                if(in_array($fetch_user->role_id,$user_role)){
                    return (new ResponseHandler)->sendSuccessResponse(in_array($fetch_user->role_id,$user_role) ? true : false);
                }
                else
                {
                    return response()->json([
                        "error" => true,
                        "data" => 'Unauthenticated',
                        "status_code"=> 1000
                    ])->setStatusCode(401);

                }

            }
            else
            {

                return response()->json([
                    "error" => true,
                    "data" => 'Unauthenticated',
                    "status_code"=> 1000
                ])->setStatusCode(401);
            }

    }

    public function fetchActivityLog(Request $request){

        try {

            $fetchActivityLog = DB::table('activity_logs')
            ->where('username',Auth::user()->name)
            ->get()
            ->map(function ($logMsg) {
                $logMsg->logDetails = str_replace(['logDetails','username','time'], [$logMsg->logDetails,$logMsg->username,$logMsg->created_at], $this->logConst['activity_msg']);
                return $logMsg;
            });

            return (new ResponseHandler)->sendSuccessResponse(['fetchActivityLog' => $fetchActivityLog], 200);

        }catch(\Exception $e){
            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function getAllUser(Request $request){

        $getAllUser = User::select('id','name','email','phoneNo','userPhoto','gender','dob')->get();

        return (new ResponseHandler)->sendSuccessResponse(['getAllUser' => $getAllUser], 200);

    }

    public function getUserByID(Request $request,$user_id){

        $getUserByID = User::where('id',$user_id)->select('id','name','email','phoneNo','userPhoto','gender','dob')->first();

        return (new ResponseHandler)->sendSuccessResponse(['getUserByID' => $getUserByID], 200);

    }

    public function updateUserProfileInfo(updateProfile $request,$user_id){


        if($request->hasFile('userPhoto')){

            $userPhotoFile = $request->file('userPhoto');
            $userPhoto =  str_replace(' ', '', $userPhotoFile->getClientOriginalName());
            $userPhotoFile->move(public_path(env('USER_PHOTO_PATH').'/'.$request->name), $userPhoto);
            $userPhotoPath = asset(env('USER_PHOTO_PATH').'/'.$request->name).'/'.$userPhoto;
            $checkImage = User::where('id',$user_id)->first();

            if($userPhotoPath != $checkImage->userPhoto){

                User::where('id', $user_id)->update([
                    // 'name' => $request->name,
                    'email' => $request->email,
                    'phoneNo' => $request->phoneNo,
                    // 'gender' => $request->gender,
                    // 'dob' => $request->dob,
                    'userPhoto' => $userPhotoPath ? $userPhotoPath : $checkImage->userPhoto
                ]);

                if(File::exists(public_path(substr($checkImage->userPhoto,22))))
                {
                    $deleteFile = public_path(substr($checkImage->userPhoto,22));
                    File::delete($deleteFile);
                }
            }
        }
        else
        {

            User::where('id', $user_id)->update([
                // 'name' => $request->name,
                'email' => $request->email,
                'phoneNo' => $request->phoneNo,
                // 'gender' => $request->gender,
                // 'dob' => $request->dob,
            ]);

        }

        return (new ResponseHandler)->sendSuccessResponse($this->const['USER_PROFILE_UPDATE'], 200);


    }


}
