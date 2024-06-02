<?php

namespace App\Http\Utils;

use Illuminate\Support\Facades\Auth;

class Authorize
{

    public function checkAdmin()
    {

        $admin_role =  explode(",", env('ADMIN_ROLE'));
        if (auth('sanctum')->user() && in_array(auth('sanctum')->user()->role_id, $admin_role))
            return true;
        return false;
    }

    public function checkUser()
    {
        $user_role =  explode(",", env('USER_ROLE'));
        if (in_array(auth('sanctum')->user()->role_id, $user_role))
            return true;
        return false;
    }

    public function checkNotLogin()
    {

        if (!auth('sanctum')->user())
            return true;
        return false;
    }


}
