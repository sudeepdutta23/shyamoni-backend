<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryMaster;
use App\Http\Requests\{
    Category
};
use App\Http\Utils\{
    ResponseHandler
};
use App\Models\{
    addtoCart,
    Constants,
    logConstants
};
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
class CategoryCrudController extends Controller
{


    private $const,$logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();

        $this->logConst = (new logConstants)->getLogConstants();
    }

    public function fetchCategory()
    {

        try {
            $fetchCategory = CategoryMaster::orderBy('id', 'asc')->get();
            if($fetchCategory)
                return (new ResponseHandler)->sendSuccessResponse($fetchCategory, 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function addCategory(Category $request)
    {
        try {


                if($request->hasFile('categoryImage')){

                    $categoryImageFile = $request->file('categoryImage');
                    $categoryImage = $categoryImageFile->getClientOriginalName();
                    $categoryImageFile->move(public_path(env('CATEGORY_IMAGE_PATH')),$categoryImage);
                    $categoryImagePath = asset(env('CATEGORY_IMAGE_PATH')).'/'.$categoryImage;


                    $addCategory = CategoryMaster::create([
                        'categoryName' => $request->categoryName,
                        'categoryImage'=>  $categoryImagePath,
                        'status' => 1,
                    ]);

                }else
                {

                    $addCategory = CategoryMaster::create([
                        'categoryName' => $request->categoryName,
                        'status' => 1,
                    ]);

                }


            if($addCategory){
                $message = "Category Added by {{username}}";

                $string = str_replace('{{username}}',auth('sanctum')->user()->name, $message);

                DB::table('activity_logs')->insert([
                    'user_id'=>auth('sanctum')->user()->id,
                    'activity'=>$string,
                    'requestResponse'=>json_encode($request->all()),
                ]);

            }


                return (new ResponseHandler)->sendSuccessResponse($this->const['CATEGORY_ADD'], 200);
        } catch (\Exception $e) {
            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function editCategory(Category $request, $id)
    {

        try {


            $beforeEdit = CategoryMaster::where('id', $id)
            ->first()
            ->toArray();



                if($request->hasFile('categoryImage')){

                    $imagePath = CategoryMaster::where('id', $id)->first();

                    if(File::exists(public_path(substr($imagePath->categoryImage,22)))){
                        $deleteFile = public_path(substr($imagePath->categoryImage,22));
                        File::delete($deleteFile);
                    }

                    $categoryImageFile = $request->file('categoryImage');
                    $categoryImage = $categoryImageFile->getClientOriginalName();
                    $categoryImageFile->move(public_path(env('CATEGORY_IMAGE_PATH')),$categoryImage);
                    $categoryImagePath = asset(env('CATEGORY_IMAGE_PATH')).'/'.$categoryImage;

                    $updateCategory = CategoryMaster::where('id', $id)
                    ->update(
                        [
                            'categoryName' => $request->categoryName,
                            'categoryImage'=>  $categoryImagePath,
                        ]
                    );

                }else
                {

                    $updateCategory = CategoryMaster::where('id', $id)
                    ->update(
                        [
                            'categoryName' => $request->categoryName,
                        ]
                    );
                }




            if ($updateCategory)

                $afterEdit = CategoryMaster::where('id', $id)
                ->first()
                ->toArray();

                $collection = collect($beforeEdit);

                $diff = collect($afterEdit)->diff($collection);

                $message = "Category Updated by {{username}}";

                $string = str_replace('{{username}}',auth('sanctum')->user()->name, $message);

                DB::table('activity_logs')->insert([
                    'user_id'=> auth('sanctum')->user()->id,
                    'activity'=>$string,
                    'requestResponse'=>json_encode($diff),
                ]);

                return (new ResponseHandler)->sendSuccessResponse($this->const['CATEGORY_UPDATE'], 200);

        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }


    public function deleteCategory(Request $request, $id)
    {

        try {


            $deleteCategory = CategoryMaster::find($id)->delete();

            if ($deleteCategory)

                $message = "Category Deleted by {{username}}";

                $string = str_replace('{{username}}',auth('sanctum')->user()->name, $message);

                DB::table('activity_logs')->insert([
                    'user_id'=>auth('sanctum')->user()->id,
                    'activity'=>$string,
                    'requestResponse'=>json_encode($request->all()),
                ]);

                return (new ResponseHandler)->sendSuccessResponse($this->const['DELETE_CATEGORY'], 200);
        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function getCategoryByID($id)
    {

        try {

            $getCategoryByID = CategoryMaster::where('id', $id)->where('status', 1)->select('id', 'categoryName', 'status')->first();
            if($getCategoryByID)
                return (new ResponseHandler)->sendSuccessResponse($getCategoryByID, 200);
        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }
    }
}
