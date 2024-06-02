<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ShipRocketController;
use App\Http\Controllers\Api\IthinkLogisticController;
use Request;

class DeliveryVendorController extends Controller
{

    public function createOrder($orderID,$request){

        return  env('vendorName') == 'ithink' ?  (new IthinkLogisticController)->createOrder($orderID,$request) : (new ShipRocketController)->createOrder($orderID,$request);

    }

    public function trackOrder($awb_id){

        return env('vendorName') == 'ithink' ? (new IthinkLogisticController)->trackOrder($awb_id) : (new ShipRocketController)->trackOrder($awb_id);

    }

    public function createLocalOrder($orderID){

        return env('vendorName') == 'ithink' ? (new IthinkLogisticController)->createLocalOrder($orderID) : (new ShipRocketController)->createLocalOrder($orderID);

    }

    public function getState(){
        return  (new IthinkLogisticController)->getState();
    }

    public function getCity($state_id){
        return  (new IthinkLogisticController)->getCity($state_id);
    }

    public function checkPinCode($pincode){
        return  (new IthinkLogisticController)->checkPinCode($pincode);
    }

    public function cancelOrder($orderid,$request){

        return env('vendorName') == 'ithink' ? (new IthinkLogisticController)->trackOrderStatus($orderid,$request) : (new ShipRocketController)->trackOrderStatus($orderid,$request);

    }







}
