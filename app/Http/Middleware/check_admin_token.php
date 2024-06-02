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

class check_admin_token
{


    private $const,$logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    public function handle(Request $request, Closure $next)
    {
        // return $next($request);

        try{


            $admin_role = explode(",",env('ADMIN_ROLE'));

            if (!empty(auth('sanctum')->user()->role_id) && in_array((auth('sanctum')->user()->role_id),$admin_role)) {

                return $next($request);
            }
            else{

                return response()->json([
                    "error" => true,
                    "data" => 'Unauthenticated',
                    "status_code"=> 1000
                ])->setStatusCode(401);

            }

        }catch (Exception $e) {

            return response()->json([
                "error" => true,
                "data" => 'Unauthenticated',
                "status_code"=> 1000
            ])->setStatusCode(401);

        }

    }
}
