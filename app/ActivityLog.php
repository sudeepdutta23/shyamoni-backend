<?php


use App\Models\DeliveryVendor;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

use App\Models\ActivityLogModel;

function addToLog($constant){
        $log = [];
    	// $log['logDetails'] = $subject;
    	// $log['method'] = Request::method();
    	$log['username'] = Auth::user()->name;
    	$log['role_id'] = Auth::user()->role_id;
    	$log['constant'] = $constant;
    	ActivityLogModel::create($log);
}

// function addToLog(){
//     $log = [];
//     $log['logDetails'] = Auth::user()->name;
//     $log['method'] = Request::method();
//     $log['user_id'] = Auth::user()->id;
//     $log['role_id'] = Auth::user()->role_id;
//     ActivityLogModel::create($log);
// }



?>
