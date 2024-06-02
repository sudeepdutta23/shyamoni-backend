<?php

namespace App\Http\Controllers\Api;


use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    ProductTable,
    ProductImage,
    Constants,
    logConstants,
    userFeedback,
    CategoryMaster,
    stockTable,
    addtoCart,
    productTags,
    productWeight,
    orderTable,
    subCategoryMaster
};



use App\Http\Requests\{
    Search,
    Product,
};

use App\Http\Utils\{
    Authorize,
    ResponseHandler
};

use App\{
    helpers,
};
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PhpParser\Node\Stmt\TryCatch;

class productController extends Controller
{

    private $const,$logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();

        $this->logConst = (new logConstants)->getLogConstants();

    }

    public function fetchProduct(Request $request)
    {
        try {


                $finalproduct = [];

                $pageOffset = (isset($request->pageOffset)) ? (int) $request->pageOffset : 12;

                $fetchProductUnderCategory =  $fetchProductUnderSubCategory =  $fetchProduct = $fetchAllTag = [];


                if(!empty($request->input('terms'))){

                    $fetchCategory = DB::table('category_masters')
                    ->join('product_table','product_table.cate_id','=','category_masters.id')
                    ->where('categoryName','LIKE', '%' . $request->input('terms') . '%')
                    ->select('product_table.id as product_id')
                    ->get();

                    if(count($fetchCategory)>0){
                        $fetchProductUnderCategory = filterProduct($fetchCategory,$request);

                    }
                }

                if(!empty($request->input('terms'))){

                    $fetchSubCategory =  DB::table('sub_category_masters')
                    ->join('product_table','product_table.subCate_id','sub_category_masters.id')
                    ->where('subCategory_name','LIKE', '%' . $request->input('terms') . '%')
                    ->select('product_table.id as product_id')
                    ->get();

                    if(count($fetchSubCategory)>0){
                        $fetchProductUnderSubCategory = filterProduct($fetchSubCategory,$request);
                    }
                }


                $fetchAllProductID = DB::table('product_table');

                if(!empty($request->input('terms'))){


                    $fetchAllProductID = $fetchAllProductID
                    ->where('productName','LIKE', '%' . $request->input('terms') . '%')
                    ->select('id as product_id')
                    ->get();
                }
                else
                {


                    $fetchAllProductID = $fetchAllProductID
                    ->select('id as product_id')
                    ->get();


                }

                if(count($fetchAllProductID)>0){

                    $fetchProduct = filterProduct($fetchAllProductID,$request);

                }



                    if(!empty($request->input('terms'))){

                        $ProductIDUnderTag = DB::table('tags')
                        ->where('tag_name','LIKE', '%' . $request->input('terms') . '%')
                        ->select('product_id')
                        ->get();


                        if(count($ProductIDUnderTag)>0){

                            $fetchAllTag = filterProduct($ProductIDUnderTag,$request);

                        }
                    }


                    $finalproduct =  collect($fetchProductUnderCategory)->concat($fetchProductUnderSubCategory)->concat($fetchProduct)->concat($fetchAllTag)->unique();


                    if(count($finalproduct) > 0){

                        $getFilterObject = getFilterObject($finalproduct->toArray());

                        return (new ResponseHandler)->sendSuccessResponse(['filters'=>$getFilterObject,'searchItems'=>$finalproduct->paginate($pageOffset)], 200);

                    }else
                    {
                        return (new ResponseHandler)->sendSuccessResponse(['searchItems'=>$finalproduct->paginate($pageOffset)], 200);

                    }

        } catch (\Exception $e) {
            dd($e);

            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function getAllProductForStock(Request $request){
        try {

            $getAllProductForStock = ProductTable::orderBy('id', 'desc')
            ->select('id','productName')
            ->get();

            return (new ResponseHandler)->sendSuccessResponse($getAllProductForStock, 200);
        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function fetchSingleProduct(Request $request, $id)
    {
        try {

            $weightColumn = 'product_weight';

            $fetchSingleProduct = ProductTable::with('productImage:product_id,ImagePath','product_tags:product_id,tag_name,id')
            ->where('id',$id)
            ->where('product_table.product_status',1)
            ->select('id','cate_id','subCate_id','productName','brand','shortDesc','longDesc','keywords')
            ->addSelect([
                'stars' => userFeedback::select(DB::Raw("ifnull(floor(avg(userRating)), 1) as userRating")
                        )->whereColumn('product_id', 'product_table.id')
                    ->limit(1),

                'reviews' => userFeedback::select(DB::raw('count(comment)'))->whereColumn('product_id', 'product_table.id')
                ->limit(1),


            ])->first();


            if(!empty($fetchSingleProduct->cate_id)){

                $fetchSuggestedProducts = ProductTable::with('productImage:product_id,ImagePath',$weightColumn)
                ->where('cate_id',$fetchSingleProduct->cate_id)
                ->where('product_table.product_status',1)
                ->where('product_table.id','!=',$id)
                ->addSelect([
                    'stars' => userFeedback::select(DB::raw('floor(avg(userRating))'))->whereColumn('product_id', 'product_table.id')
                        ->limit(1),
                    'reviews' => userFeedback::select(DB::raw('count(comment)'))->whereColumn('product_id', 'product_table.id')
                        ->limit(1)
                ])
                ->get();
            }else
            {
                return (new ResponseHandler)->sendSuccessResponse($this->const['NO_PRODUCT'], 404);
            }


            $fetchSingleProduct->product_weight = productWeight::where('product_id',$id)
            ->select('id','weight','specialPrice','originalPrice','discountAmount','deliveryCharge','gst')
            ->addSelect([
                'availableStock' => stockTable::select(DB::raw('SUM(stock_in-stock_out)'))
                ->whereColumn('product_weight', 'product_weight.id')
                ->limit(1),
            ])
            ->groupBy('product_weight.weight')
            ->get();

            return (new ResponseHandler)->sendSuccessResponse(['fetchSingleProduct'=>$fetchSingleProduct,'fetchSuggestedProducts'=>$fetchSuggestedProducts],200);

        } catch (\Exception $e) {
            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function randomFetchProduct(Request $request)
    {
        try {

            $pageOffset = (isset($request->pageOffset)) ? (int) $request->pageOffset : 12;

            $weightColumn = 'product_weight';

            $randomFetchProduct = ProductTable::with('productImage','product_tags:product_id,id,tag_name,id',$weightColumn)->addSelect([
                'stars' => userFeedback::select(DB::raw('floor(avg(userRating))'))->whereColumn('product_id', 'product_table.id')
                    ->limit(1),
                'reviews' => userFeedback::select(DB::raw('count(comment)'))->whereColumn('product_id', 'product_table.id')
                    ->limit(1),
            ])
            ->where('product_status',1)
            ->inRandomOrder()
            ->paginate($pageOffset);

            return (new ResponseHandler)->sendSuccessResponse($randomFetchProduct, 200);
        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function addProduct(Product $request)
    {

        try {


                    $allObject = json_decode($request->product_weight[0]);

                    $images = $request->file('ImagePath');

                    // Check Image Count
                    if(count($images) > 5)
                        return (new ResponseHandler)->sendErrorResponse($this->const['IMAGE_UPLOAD_EXCEED'], 500);


                    $storeProduct = ProductTable::create([
                        'cate_id' => $request->Category,
                        'subCate_id' => $request->subCategory,
                        'productName' => $request->productName,
                        'brand' => $request->brand,
                        'shortDesc' => $request->shortDesc,
                        'longDesc' => $request->longDesc,
                        'keywords' => $request->keywords,
                        'product_status' => 1
                    ]);


                    if ($storeProduct) {

                        if($request->hasfile("ImagePath")) {

                            $images = $request->file('ImagePath');
                            foreach ((array)$images as $image) {
                                $image_new_name = str_replace(' ', '', $image->getClientOriginalName());
                                $image->move(public_path(env('IMAGE_PRODUCT_PATH')), $image_new_name);
                                $imagePath =  asset(env('IMAGE_PRODUCT_PATH')).'/'.$image_new_name;

                                $storeImage = DB::table('productimages')->insert([
                                    'product_id' => $storeProduct->id,
                                    'ImagePath' => $imagePath,
                                    'status' => 1
                                ]);

                            }

                        }

                        $tags = explode(",",$request->tag_name);

                        foreach($tags as $key=>$value) {
                           $storeTag = DB::table('tags')->insert([
                                'product_id'=> $storeProduct->id,
                                'tag_name'=> $tags[$key],
                            ]);
                        }


                        // Adding Weight
                        foreach($allObject as $key=>$value) {

                            $SKUID = SKUIDcheck();

                            $product_weight = DB::table('product_weight')->insertGetId([
                                'product_id'=> $storeProduct->id,
                                'weight'=> $allObject[$key]->weight,
                                'specialPrice'=> $allObject[$key]->specialPrice,
                                'originalPrice'=> $allObject[$key]->originalPrice,
                                'discountAmount'=> $allObject[$key]->discountAmount,
                                'product_sku'=> $SKUID,
                                'deliveryCharge'=> $allObject[$key]->deliveryCharge,
                                'gst'=> $allObject[$key]->gst,
                            ]);



                            if($product_weight){
                                $storeStock = DB::table('stock_tables')->insert([
                                    'product_id'=> $storeProduct->id,
                                    'product_weight' => $product_weight,
                                    'stock_in'=> $allObject[$key]->quantity,
                                    'stock_status' => 1,
                                ]);
                            }
                        }

                    }


                    $message = "Product added by {{username}}";

                    $string = str_replace('{{username}}',auth('sanctum')->user()->name, $message);

                    DB::table('activity_logs')->insert([
                        'user_id'=>auth('sanctum')->user()->id,
                        'activity'=>$string,
                        'requestResponse'=>json_encode($request->all()),
                    ]);

                return (new ResponseHandler)->sendSuccessResponse($this->const['ADD_PRODUCT'], 200);

        } catch (\Exception $e) {

            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function editProduct(Product $request, $id)
    {

        try {



                $beforeedit = ProductTable::where('id',$id)
                ->first()
                ->toArray();


                $images = $request->file('ImagePath');

                // Check Image Count
                if($images)
                {

                    if(count($images) > 5)
                        return (new ResponseHandler)->sendErrorResponse($this->const['IMAGE_UPLOAD_EXCEED'], 500);

                }

                    $updateProduct = ProductTable::where('id', $id)->update([
                        'cate_id' => $request->Category,
                        'subCate_id' => $request->subCategory,
                        'productName' => $request->productName,
                        'brand' => $request->brand,
                        'shortDesc' => $request->shortDesc,
                        'longDesc' => $request->longDesc,
                        'keywords' => $request->keywords,
                    ]);


                    // Testing

                    if($images){
                            if ($updateProduct) {

                                $checkProductID = DB::table('productimages')
                                ->where('product_id',$id)
                                ->where('deleted_at',NULL)
                                ->count();

                                if($checkProductID < 5){

                                    $images = $request->file('ImagePath');
                                    foreach ((array)$images as $image) {
                                        $image_new_name = $image->getClientOriginalName();
                                        $image->move(public_path(env('IMAGE_PRODUCT_PATH')), $image_new_name);
                                        $imagePath =  asset(env('IMAGE_PRODUCT_PATH')).'/'.$image_new_name;

                                        $storeImage = DB::table('productimages')->insert([
                                            'product_id' => $id,
                                            'ImagePath' => $imagePath,
                                            'status' => 1
                                        ]);


                                    }


                                }else
                                {
                                        return (new ResponseHandler)->sendErrorResponse($this->const['IMAGE_UPLOAD_EXCEED'], 500);

                                }
                        }

                    }


                    $afteredit = ProductTable::where('id',$id)
                    ->first()
                    ->toArray();

                    $collection = collect($beforeedit);

                    $diff = collect($afteredit)->diff($collection);

                    $message = "Product Edited by {{username}}";

                    $string = str_replace('{{username}}',auth('sanctum')->user()->name, $message);

                    DB::table('activity_logs')->insert([
                        'user_id'=>auth('sanctum')->user()->id,
                        'activity'=>$string,
                        'requestResponse'=>json_encode($diff),
                    ]);


                    return (new ResponseHandler)->sendSuccessResponse($this->const['UPDATE_PRODUCT'], 200);



        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function editProductWeight(Request $request,$product_id){

        try {

            // $beforeEditWeight = productWeight::where('product_id',$product_id)
            // ->first()
            // ->toArray();

            $allObject = $request->product_weight;


            if(has_dupes($allObject)){

                return (new ResponseHandler)->sendSuccessResponse($this->const['WEIGHT_EXISTS'], 200);

            }else
            {
                foreach($allObject as $key=>$value) {
                    if(!empty($allObject[$key]['id'])){

                        $editProductWeight = productWeight::where('product_id',$product_id)
                        ->where('id',$allObject[$key]['id'])
                        ->update([
                            'specialPrice'=> $allObject[$key]['specialPrice'],
                            'originalPrice'=> $allObject[$key]['originalPrice'],
                            'discountAmount'=> $allObject[$key]['discountAmount'],
                        ]);
                    }else if(empty($allObject[$key]['id']))
                    {

                    //    foreach($allObject as $key=>$value) {

                            $SKUID = SKUIDcheck();

                            $data = array(
                                'product_id'=> $product_id,
                                'weight'=> $allObject[$key]['weight'],
                                'specialPrice'=> $allObject[$key]['specialPrice'],
                                'originalPrice'=> $allObject[$key]['originalPrice'],
                                'discountAmount'=> $allObject[$key]['discountAmount'],
                                'product_sku'=> $SKUID,
                            );

                            $new_product_weight_insert = productWeight::insertGetId($data);

                            if($new_product_weight_insert){


                                // $message = "Product Weight Newly Inserted by {{username}}";

                                // $string = str_replace('{{username}}',auth('sanctum')->user()->name, $message);

                                // DB::table('activity_logs')->insert([
                                //     'user_id'=>auth('sanctum')->user()->id,
                                //     'activity'=>$string,
                                //     'requestResponse'=>json_encode($data),
                                // ]);

                                DB::table('stock_tables')->insert([
                                    'product_id'=> $product_id,
                                    'stock_in'=> $allObject[$key]['quantity'],
                                    'product_weight' => $new_product_weight_insert
                                ]);
                            }

                            // if($new_product_weight_insert)

                            //     return (new ResponseHandler)->sendSuccessResponse($this->const['NEW_WEIGHT_INSERTED'], 200);
                        }


                    // }

                }


            // if($editProductWeight)


                // $afterEditWeight = productWeight::where('product_id',$product_id)
                // ->first()
                // ->toArray();

                // $collection = collect($beforeEditWeight);

                // $diff = collect($afterEditWeight)->diff($collection);

                // $message = "Product Edited by {{username}}";

                // $string = str_replace('{{username}}',auth('sanctum')->user()->name, $message);

                // DB::table('activity_logs')->insert([
                //     'user_id'=>auth('sanctum')->user()->id,
                //     'activity'=>$string,
                //     'requestResponse'=>json_encode($diff),
                // ]);


                return (new ResponseHandler)->sendSuccessResponse($this->const['PRODUCT_WEIGHT_UPDATE'], 200);

            }


        } catch (\Exception $e) {
            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }


    }

    public function getWeightByProduct(Request $request,$product_id){

        try {

            $getWeightByProduct = productWeight::where('product_id',$product_id)
            ->select('id','product_id','weight')
            ->get();

            return (new ResponseHandler)->sendSuccessResponse($getWeightByProduct, 200);
        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }

    }

    public function fetchAllWeight(Request $request){

        try {

            $fetchAllWeight = DB::table('weight_master')->orderBy('id','asc')->select('id','weight','deliveryCharge')->get();

            return (new ResponseHandler)->sendSuccessResponse($fetchAllWeight, 200);

        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }

    }

    public function deleteProductWeight(Request $request,$id){

        try{

            // dd($id);

            $checkWeight = productWeight::where('id',$id)
            ->first();

            // dd($checkWeight->product_id);

            $countWeight = productWeight::where('product_id',$checkWeight->product_id)->get();

            if($countWeight->count() == 1){
                return (new ResponseHandler)->sendSuccessResponse($this->const['WEIGHT_DELETE_ERROR'], 200);
            }else{



                $deleteProductWeight = productWeight::where('id', $id)->delete();

                if($deleteProductWeight)

                    $message = "Product Weight Deleted by {{username}}";

                    $string = str_replace('{{username}}',auth('sanctum')->user()->name, $message);

                    DB::table('activity_logs')->insert([
                        'user_id'=>auth('sanctum')->user()->id,
                        'activity'=>$string,
                        'requestResponse'=>json_encode($request->all()),
                    ]);

                    return (new ResponseHandler)->sendSuccessResponse($this->const['DELETE_PRODUCT_WEIGHT'], 200);

                }



        } catch (\Exception $e) {
            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }

    }

    public function deleteProduct(Request $request,$id)
    {

        try {


                $imagePath = ProductImage::where('product_id', $id)->select('ImagePath')->get();

                       foreach($imagePath as $value){
                            if(File::exists(public_path(substr($value->ImagePath,22)))){
                                $deleteFile = public_path(substr($value->ImagePath,22));
                                File::delete($deleteFile);
                            }
                       }


                        ProductTable::where('id', $id)->delete();
                        ProductImage::where('product_id', $id)->delete();

                    $message = "Product Deleted by {{username}}";

                    $string = str_replace('{{username}}',auth('sanctum')->user()->name, $message);

                    DB::table('activity_logs')->insert([
                        'user_id'=>auth('sanctum')->user()->id,
                        'activity'=>$string,
                        'requestResponse'=>json_encode($request->all()),
                    ]);

                    return (new ResponseHandler)->sendSuccessResponse($this->const['DELETE_PRODUCT'], 200);

        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();

        }
    }

    public function deleteProductImage(Request $request,$id){

        try {


            $imagePath = ProductImage::where('id', $id)->first();

            if(File::exists(public_path(substr($imagePath->ImagePath,22)))){
                $deleteFile = public_path(substr($imagePath->ImagePath,22));
                File::delete($deleteFile);
            }

            $deleteProductImage = ProductImage::where('id',$id)->delete();

            if($deleteProductImage)

                $message = "Product Image Deleted by {{username}}";

                $string = str_replace('{{username}}',auth('sanctum')->user()->name, $message);

                DB::table('activity_logs')->insert([
                    'user_id'=>auth('sanctum')->user()->id,
                    'activity'=>$string,
                    'requestResponse'=>json_encode($request->all()),
                ]);

                return (new ResponseHandler)->sendSuccessResponse($this->const['DELETE_PRODUCT_IMAGE'], 200);

        }
        catch(\Exception $e)
        {

            return (new ResponseHandler)->sendErrorResponse();

        }
    }

    public function searchProduct(Request $request)
    {
        try {

            $searchResult = [];

            if(!empty($request->input('s'))){


                $searchProduct = DB::table('product_table')
                    ->where('deleted_at', '=', null)
                    ->where('product_status',1)
                    ->where('productName', 'LIKE', '%' . $request->input('s') . '%')
                    ->select('id', 'productName as name', DB::raw("'product' as type"))
                    ->get();



                $searchCategory = DB::table('category_masters')
                    ->where('deleted_at', '=', null)
                    ->where('categoryName', 'LIKE', '%' . $request->input('s') . '%')
                    ->select('id', 'categoryName as name', DB::raw("'category' as type"))
                    ->get();



                $searchSubCategory = DB::table('sub_category_masters')
                    ->where('deleted_at', '=', null)
                    ->where('subCategory_name', 'LIKE', '%' . $request->input('s') . '%')
                    ->select('id', 'subCategory_name as name', DB::raw("'subCategory' as type"))
                    ->get();



                $searchTags = DB::table('tags')
                ->where('tags.deleted_at', '=', null)
                ->join('product_table','product_table.id','=','tags.product_id')
                ->where('tag_name', 'LIKE', '%' . $request->input('s') . '%')
                ->select('product_table.id', 'product_table.productName as name','tags.tag_name',DB::raw("'tag_name' as type"))
                ->get();

                $searchResult = collect($searchProduct)->concat($searchCategory)->concat($searchSubCategory)->concat($searchTags)->unique();


            }


            return (new ResponseHandler)->sendSuccessResponse($searchResult, 200);
        } catch (\Exception $e) {
            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function getProductUnderCategory(Request $request,$id){


        $fetchBrand = DB::table('product_table')
        ->where('cate_id',$id)
        ->select('brand')
        ->get();

        $minProductPrice = DB::table('product_table')
        ->join('product_weight','product_weight.product_id','=','product_table.id')
        ->where('cate_id',$id)->select(DB::raw('MIN(specialPrice) AS minPrice'))
        ->get();

        $maxProductPrice = DB::table('product_table')
        ->join('product_weight','product_weight.product_id','=','product_table.id')
        ->where('cate_id',$id)->select(DB::raw('MAX(specialPrice) AS maxPrice'))
        ->get();

        $fetchProduct =  ProductTable::with('product_image:id,product_id,ImagePath','product_weight')
        ->addSelect([
            'stars' => userFeedback::select(DB::raw('floor(avg(userRating))'))->whereColumn('product_id', 'product_table.id')
                ->limit(1),
            'reviews' => userFeedback::select(DB::raw('count(comment)'))->whereColumn('product_id', 'product_table.id')
                ->limit(1)
        ])
        ->where('cate_id',$id);

        if($request->brand)
            $fetchProduct = $fetchProduct->whereIn('brand',explode(',',$request->brand));

        if($request->minPrice && $request->maxPrice)

                $fetchProduct->price = $fetchProduct->with(('product_weight'),function($q) use ($request){
                    $q->whereBetween('specialPrice',[$request->minPrice,$request->maxPrice]);
                });

            $fetchProduct = $fetchProduct->get();

            return (new ResponseHandler)->sendSuccessResponse(["fetchBrand" => $fetchBrand, "minProductPrice" => $minProductPrice[0]->minPrice, "maxProductPrice"=>$maxProductPrice[0]->maxPrice,'fetchProduct'=>$fetchProduct], 200);

    }

    public function getProductUnderSubCategory(Request $request,$id){

        $fetchBrand = DB::table('product_table')
        ->where('subCate_id',$id)
        ->select('brand')
        ->get();

        $minProductPrice = DB::table('product_table')
        ->join('product_weight','product_weight.product_id','=','product_table.id')
        ->where('subCate_id',$id)
        ->select(DB::raw('MIN(specialPrice) AS minPrice'))
        ->get();

        $maxProductPrice = DB::table('product_table')
        ->join('product_weight','product_weight.product_id','=','product_table.id')
        ->where('subCate_id',$id)
        ->select(DB::raw('MAX(specialPrice) AS maxPrice'))
        ->get();

        $fetchProduct =  ProductTable::with('product_image:product_id,ImagePath','product_weight')
        ->addSelect([
            'stars' => userFeedback::select(DB::raw('floor(avg(userRating))'))->whereColumn('product_id', 'product_table.id')
                ->limit(1),
            'reviews' => userFeedback::select(DB::raw('count(comment)'))->whereColumn('product_id', 'product_table.id')
                ->limit(1)
        ])
        ->where('subCate_id',$id);


        if($request->brand)
            $fetchProduct =  $fetchProduct->whereIn('brand',explode(',',$request->brand));

        if($request->minPrice && $request->maxPrice)

            $fetchProduct->price = $fetchProduct->with(('product_weight'),function($q) use ($request){
                $q->whereBetween('specialPrice',[$request->minPrice,$request->maxPrice]);
            });

        $fetchProduct = $fetchProduct->get();

        return (new ResponseHandler)->sendSuccessResponse(["fetchBrand" => $fetchBrand, "minProductPrice" => $minProductPrice[0]->minPrice, "maxProductPrice"=>$maxProductPrice[0]->maxPrice,'fetchProduct'=>$fetchProduct], 200);

    }


    public function filterProduct(Request $request){


        try {

            $finalproduct = [];

            if(!empty($request->input('terms'))){

                $pageOffset = (isset($request->pageOffset)) ? (int) $request->pageOffset : 12;


                $fetchProductUnderCategory =  $fetchProductUnderSubCategory =  $fetchProduct = $fetchAllTag = [];


                $fetchCategory = DB::table('category_masters')
                ->join('product_table','product_table.cate_id','=','category_masters.id')
                ->where('categoryName','LIKE', '%' . $request->input('terms') . '%')
                ->select('product_table.id as product_id')
                ->get();

                if(count($fetchCategory)>0){
                    $fetchProductUnderCategory = filterProduct($fetchCategory,$request);

                }


                $fetchSubCategory =  DB::table('sub_category_masters')
                ->join('product_table','product_table.subCate_id','sub_category_masters.id')
                ->where('subCategory_name','LIKE', '%' . $request->input('terms') . '%')
                ->select('product_table.id as product_id')
                ->get();

                if(count($fetchSubCategory)>0){
                    $fetchProductUnderSubCategory = filterProduct($fetchSubCategory,$request);
                }

               $fetchAllProductID = DB::table('product_table')
               ->where('productName','LIKE', '%' . $request->input('terms') . '%')
               ->select('id as product_id')
               ->get();


               if(count($fetchAllProductID)>0){

                    $fetchProduct = filterProduct($fetchAllProductID,$request);

               }

               $ProductIDUnderTag = DB::table('tags')
               ->where('tag_name','LIKE', '%' . $request->input('terms') . '%')
               ->select('product_id')
               ->get();


               if(count($ProductIDUnderTag)>0){

                    $fetchAllTag = filterProduct($ProductIDUnderTag,$request);

                }


                $finalproduct =  collect($fetchProductUnderCategory)->concat($fetchProductUnderSubCategory)->concat($fetchProduct)->concat($fetchAllTag)->unique();


                if(count($finalproduct) > 0){

                    $getFilterObject = getFilterObject($finalproduct->toArray());

                    return (new ResponseHandler)->sendSuccessResponse(['filters'=>$getFilterObject,'searchItems'=>$finalproduct->paginate($pageOffset)->toArray()], 200);

                }else
                {
                    return (new ResponseHandler)->sendSuccessResponse(['searchItems'=>$finalproduct->paginate($pageOffset)->toArray()], 200);

                }

            }


        }
        catch(\Exception $e){
            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }


    }

    public function getImagesByProductID(Request $request,$product_id){
        try{

            $getImagesByProductID = ProductImage::where('product_id',$product_id)->select('id','ImagePath')->get();
            return (new ResponseHandler)->sendSuccessResponse($getImagesByProductID, 200);

        }catch(\Exception $e){
            return (new ResponseHandler)->sendErrorResponse();
        }

    }

    public function deleteTags(Request $request,$id){

        $deleteTags = productTags::where('id',$id)->delete();

        if($deleteTags)

                $message = "Product Tags Deleted by {{username}}";

                $string = str_replace('{{username}}',auth('sanctum')->user()->name, $message);

                DB::table('activity_logs')->insert([
                    'user_id'=>auth('sanctum')->user()->id,
                    'activity'=>$string,
                    'requestResponse'=>json_encode($request->all()),
                ]);

            return (new ResponseHandler)->sendSuccessResponse($this->const['PRODUCT_TAG_DELETE'], 200);

    }

    public function addIndividualTag(Request $request,$product_id){

        $checkProductTags = productTags::where('product_id',$product_id)->get();

        if(count($checkProductTags) == 10)
            return (new ResponseHandler)->sendErrorResponse($this->const['TAG_UPLOAD_EXCEED'], 500);
        else

            $addIndividualTag = productTags::create([
                'product_id'=>$product_id,
                'tag_name'=>$request->tag_name,
            ]);

            if($addIndividualTag){

                $message = "Indiviaul Product Tag Added by {{username}}";

                $string = str_replace('{{username}}',auth('sanctum')->user()->name, $message);

                DB::table('activity_logs')->insert([
                    'user_id'=>auth('sanctum')->user()->id,
                    'activity'=>$string,
                    'requestResponse'=>json_encode($request->all()),
                ]);

                $allProductTag = productTags::where('product_id',$product_id)->select('id','product_id','tag_name')->get();

                return (new ResponseHandler)->sendSuccessResponse(['allProductTag'=>$allProductTag],200);

            }

    }

    public function getTagsByProductID(Request $request,$product_id){
        try{

            $getTagsByProductID = productTags::where('product_id',$product_id)->select('id','product_id','tag_name')->get();
            return (new ResponseHandler)->sendSuccessResponse($getTagsByProductID, 200);

        }catch(\Exception $e){
            return (new ResponseHandler)->sendErrorResponse();
        }

    }

    public function updateProductStatus(Request $request,$product_id){

        try {

            $updateProductStatus = ProductTable::where('id', $product_id)
            ->update(['product_status' => $request->all()[0]]);

                if($updateProductStatus)

                    $message = "Product Status Updated by {{username}}";

                    $string = str_replace('{{username}}',auth('sanctum')->user()->name, $message);

                    DB::table('activity_logs')->insert([
                        'user_id'=>auth('sanctum')->user()->id,
                        'activity'=>$string,
                        'requestResponse'=>json_encode($request->all()),
                    ]);

                    return (new ResponseHandler)->sendSuccessResponse($this->const[$request->all()[0] ? 'PRODUCT_STATUS_ACTIVE':'PRODUCT_STATUS_DEACTIVE'], 200);



        } catch (\Exception $e) {

            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }


    }

    public function catSubCatFetch(Request $request){

      $category = CategoryMaster::select('id','categoryName','categoryImage')
      ->with('AllsubCategory:id,category_id,subCategory_name')->get();


      return (new ResponseHandler)->sendSuccessResponse(["AllCategory" => $category], 200);


    }

    public function getGST(Request $request){

      return (new ResponseHandler)->sendSuccessResponse(["gst" => (int)env('gst')], 200);

    }


}
