<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


use App\Models\{

    Constants,
};

use App\Http\Utils\{
    Authorize,
    ResponseHandler
};
use Exception;

class AuthUser
{



    private $const,$logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }


    public function handle(Request $request, Closure $next)
    {

        try{

            $user_role = explode(",",env('USER_ROLE'));

            if(!empty(auth('sanctum')->user()) && !empty(auth('sanctum')->user()->id) && in_array((auth('sanctum')->user()->role_id),$user_role)) {

                $request['userID']=auth('sanctum')->user()->id;
                $request['role_id']=auth('sanctum')->user()->role_id;
                $request['user_email_id']=auth('sanctum')->user()->email;
                $request['Authorization']=$request->header('Authorization');

                return $next($request);
            }
            else
            {
                $request['Authorization']=$request->header('Authorization');
                return $next($request);
            }


        }catch(Exception $e){

            return response()->json([
                "error" => true,
                "data" => 'Unauthenticated',
                "status_code"=> 1000
            ])->setStatusCode(401);
        }



    }
}
