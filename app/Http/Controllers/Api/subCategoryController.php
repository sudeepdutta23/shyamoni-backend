<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

use App\Http\Utils\{
    ResponseHandler
};

use App\Models\{
    subCategoryMaster,
    logConstants,
    Constants
};


use App\Http\Requests\{
    SubCategory
};



class subCategoryController extends Controller
{

    private $const,$logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();

        $this->logConst = (new logConstants)->getLogConstants();

    }

    public function fetchSubCategory()
    {
        try {
            $fetchSubCategory = subCategoryMaster::join('category_masters','category_masters.id','=','sub_category_masters.category_id')->orderBy('sub_category_masters.id', 'asc')
            ->get();
            return (new ResponseHandler)->sendSuccessResponse($fetchSubCategory, 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }


    public function addSubCategory(SubCategory $request)
    {
        try {


            $addSubCategory = subCategoryMaster::create([
                'category_id' => $request->Category,
                'subCategory_name' => $request->subCategory_name,
                'status' => 1,
            ]);

            if ($addSubCategory)

                $message = "SubCategory Added by {{username}}";

                $string = str_replace('{{username}}',auth('sanctum')->user()->name, $message);

                DB::table('activity_logs')->insert([
                    'user_id'=>auth('sanctum')->user()->id,
                    'activity'=>$string,
                    'requestResponse'=>json_encode($request->all()),
                ]);

                return (new ResponseHandler)->sendSuccessResponse($this->const['SUB_CATEGORY_ADD'], 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function editSubCategory(SubCategory $request, $id)
    {
        try {

            $beforeEdit = subCategoryMaster::where('id', $id)
            ->first()
            ->toArray();

            $updateSubCategory = subCategoryMaster::where('id', $id)->update(['category_id' => $request->Category, 'subCategory_name' => $request->subCategory_name]);

            if ($updateSubCategory)

                    $afterEdit = subCategoryMaster::where('id', $id)
                    ->first()
                    ->toArray();

                    $collection = collect($beforeEdit);

                    $diff = collect($afterEdit)->diff($collection);

                    $message = "SubCategory updated by {{username}}";

                    $string = str_replace('{{username}}',auth('sanctum')->user()->name, $message);

                    DB::table('activity_logs')->insert([
                        'user_id'=>auth('sanctum')->user()->id,
                        'activity'=>$string,
                        'requestResponse'=>json_encode($diff),
                    ]);

                return (new ResponseHandler)->sendSuccessResponse($this->const['SUB_CATEGORY_UPDATE'], 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }


    public function deleteSubCategory(Request $request,$id)
    {
        try {

            $deleteSubCategory = subCategoryMaster::find($id)->delete();

            $message = "SubCategory deleted by {{username}}";

            $string = str_replace('{{username}}',auth('sanctum')->user()->name, $message);

            DB::table('activity_logs')->insert([
                'user_id'=>auth('sanctum')->user()->id,
                'activity'=>$string,
                'requestResponse'=>json_encode($request->all()),
            ]);

            if ($deleteSubCategory)
                return (new ResponseHandler)->sendSuccessResponse($this->const['SUB_CATEGORY_DELETE'], 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function SubCategoryByID(Request $request, $id)
    {
        try {
            $SubCategoryByID = subCategoryMaster::where('category_id', $id)->select('id', 'subCategory_name')->get();
            return (new ResponseHandler)->sendSuccessResponse($SubCategoryByID, 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function getCatSubCategory(Request $request)
    {
        try {
            $sql = "select *, (Select concat('[', group_concat(json_object('id', sub_category_masters.id, 'name', sub_category_masters.subCategory_name)) , ']')
            from sub_category_masters where category_masters.id = sub_category_masters.category_id) as subcategory from category_masters where status = 1;
            ";
            $getCatSubCategory = DB::select($sql);
            return (new ResponseHandler)->sendSuccessResponse($getCatSubCategory, 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function getSubCategoryByID(Request $request,$id)
    {
        $getSubCategoryByID = subCategoryMaster::where('id',$id)->where('status',1)->select('id','category_id','subCategory_name','status')->first();

        return (new ResponseHandler)->sendSuccessResponse($getSubCategoryByID, 200);

    }


}
