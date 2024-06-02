<?php


use App\Models\DeliveryVendor;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

use App\Models\{
    ProductTable,
    ProductImage,
    productWeight,
    Constants,
    userFeedback
};

use App\Http\Utils\{
    Authorize,
    ResponseHandler
};


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



function __construct()
{
    $const = (new Constants)->getConstants();

}


function randomNumber(){
    $digits = '';
    $length = 4;
    $numbers = range(0,9);
    shuffle($numbers);
    for($i = 0; $i < $length; $i++){
        $digits .= $numbers[$i];
    }

    return $digits;
}

function getToken(){

    $shiprocketEmailId  = env("SHIPROCKET_EMAIL");

    $shiprocketPassword  =  env("SHIPROCKET_PASSWORD");


    if(DeliveryVendor::all()->last() == null){
            $response = Http::post('https://apiv2.shiprocket.in/v1/external/auth/login', [
            'header' => 'Content-Type: application/json',
            'email' => $shiprocketEmailId,
            'password' => $shiprocketPassword,
        ]);


        $dv_id    =   $response->json()['id'];
        $first_name  =   $response->json()['first_name'];
        $last_name  =   $response->json()['last_name'];
        $email   =   $response->json()['email'];
        $company_id  =   $response->json()['company_id'];
        $token  =   $response->json()['token'];
        $created_at  =   $response->json()['created_at'];



        DeliveryVendor::create([
            'dv_id'=>$dv_id,
            'first_name'=>$first_name,
            'last_name'=>$last_name,
            'email'=>$email,
            'company_id'=>$company_id,
            'token_created_at'=>$created_at,
            'token'=>$token,
        ]);

    }

    else{

        $timeNow            =   Carbon::now(new \DateTimeZone('Asia/Kolkata'));

        $lastTokenTime      =   Carbon::parse(DeliveryVendor::all()->last()->updated_at->jsonSerialize())->timezone('Asia/Kolkata')->format('Y-m-d H:i:s');


        $hoursDifference    =   $timeNow->diffInHours($lastTokenTime);


        $token              =   null;


        if($hoursDifference > 23){

            // Create new token if token more than a day old

            $response = Http::post('https://apiv2.shiprocket.in/v1/external/auth/login', [

                'header' => 'Content-Type: application/json',

                'email' => $shiprocketEmailId,

                'password' => $shiprocketPassword,

            ]);

            $dv_id    =   $response->json()['id'];
            $first_name  =   $response->json()['first_name'];
            $last_name  =   $response->json()['last_name'];
            $email   =   $response->json()['email'];
            $company_id  =   $response->json()['company_id'];
            $token  =   $response->json()['token'];
            $created_at  =   $response->json()['created_at'];


            DeliveryVendor::create([
                'dv_id'=>$dv_id,
                'first_name'=>$first_name,
                'last_name'=>$last_name,
                'email'=>$email,
                'company_id'=>$company_id,
                'token_created_at'=>$created_at,
                'token'=>$token,
            ]);

        }

        else{

            // Get current token
            $token      =   DeliveryVendor::all()->last()->token;


        }

    }

    return $token;
}

//Function FOr Creating Unique Random SKU ID

function SKUIDcheck() {
    $n=5;
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    $a='';
    for ($i = 0; $i<5; $i++)
    {
        $a .= mt_rand(0,9);
    }

    return $randomString.$a;
}


function shyamoni_order_id($length) {

    $prefix = 'Shy_';
    $key = '';
    $keys = array_merge(range(0, 9), range('a', 'z'));

    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }

    return $prefix.$key;
}


function GuzzleClient(){
    return new \GuzzleHttp\Client();
}

function has_dupes($array) {
    $dupe_array = array();

    foreach ($array as $key=>$value) {

        $test1 = '';

        $test2=null;

        if(empty($value['id'])){

            $test2 = $key;

            $test1 = $value['weight'];

            foreach($array as $key=>$value){

                if($key != $test2){
    
                    if($value['weight'] == $test1){
    
                        return true;
    
                    }
                    else
                    {
                        return false;
                    }
                }
    
            }

        }

    }


    return false;
}


function GuzzleComponent_2($method,$url,$formData){

    if($method == 'POST'){

        $client = new \GuzzleHttp\Client();

        $response = $client->request($method,$url,
        [
            'body' =>  json_encode($formData),
        ]);

        return json_decode($response->getBody(), true);

    }

}


function GuzzleComponent($method,$url,$formData){

    if($method == 'POST'){

        $client = new \GuzzleHttp\Client();

        $response = $client->request($method,$url,
        [
            'body' =>  json_encode($formData),
        ]);

        return json_decode($response->getBody(), true);

    }

}


// shipRoket Guzzle Component
function shipRoketGuzzlePostComponent($method,$url,$form_params){



    $getToken = getToken();

    if($method == 'POST'){

        $client = new \GuzzleHttp\Client();

        $response = $client->request($method,$url,
        [
            "headers"=>[
                "Authorization"=>"Bearer " .$getToken,
            ],
            'form_params'=> $form_params
        ]);

        return json_decode($response->getBody(), true);
    }

}

function filterProduct($searchArray,$request){


    if((new Authorize)->checkAdmin()){

            $searchProduct = ProductTable::with('productImage:id,product_id,ImagePath','product_weight')
            ->addSelect([
                'stars' => userFeedback::select(DB::raw('floor(avg(userRating))'))
                ->whereColumn('product_id', 'product_table.id')
                    ->limit(1),
                'reviews' => userFeedback::select(DB::raw('count(comment)'))->whereColumn('product_id', 'product_table.id')
                    ->limit(1),
            ])
            ->whereIn('product_table.id',$searchArray->pluck('product_id'));

    }else
    {

            $searchProduct = ProductTable::with('productImage:id,product_id,ImagePath','product_weight')
            ->addSelect([
                'stars' => userFeedback::select(DB::raw('floor(avg(userRating))'))
                ->whereColumn('product_id', 'product_table.id')
                    ->limit(1),
                'reviews' => userFeedback::select(DB::raw('count(comment)'))->whereColumn('product_id', 'product_table.id')
                    ->limit(1),
            ])
            ->where('product_status',1)
            ->whereIn('product_table.id',$searchArray->pluck('product_id'));
    }

    // $searchProduct = ProductTable::with('productImage:id,product_id,ImagePath','product_weight')
    // ->addSelect([
    //     'stars' => userFeedback::select(DB::raw('floor(avg(userRating))'))
    //     ->whereColumn('product_id', 'product_table.id')
    //         ->limit(1),
    //     'reviews' => userFeedback::select(DB::raw('count(comment)'))->whereColumn('product_id', 'product_table.id')
    //         ->limit(1),
    // ])
    // ->where('product_status',1)
    // ->whereIn('product_table.id',$searchArray->pluck('product_id'));

    if($request->brand){
        $searchProduct = $searchProduct->whereIn('brand',explode(',',$request->brand));
    }

    if($request->weight){
        $searchProduct = $searchProduct->with('product_weight',function($q) use ($request){
            $q->whereIn('product_weight.weight',explode(',',$request->weight));
        });
    }


    if($request->minPrice && $request->maxPrice){

        $searchProduct = $searchProduct->whereHas('product_weight',function($q) use ($request){
            $q->whereBetween('specialPrice',[$request->minPrice,$request->maxPrice]);
        });

    }

    $finalSearchProduct = $searchProduct->get();
    return  $finalSearchProduct;

}

function getFilterObject($data){

    $price = [];

    $brand = [];

    foreach($data as $key=>$value){

        // dd($value);

        $priceWeight = $data[$key]['product_weight'];
        if(!in_array($data[$key]['brand'],$brand))
        array_push($brand, $data[$key]['brand']);
        if(count($priceWeight) > 0){
            foreach ($priceWeight as $key => $value) {
                if(!in_array($priceWeight[$key]['specialPrice'],$price))
                    array_push($price,$priceWeight[$key]['specialPrice']);
            }
        }
    }

    return array('min'=>min($price),'max'=>max($price),'brand'=>$brand);
}


?>
