<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{
    imageBanner,
    Constants,
    logConstants

};
use App\Http\Utils\{
    ResponseHandler,
};
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\File;



class ImageBannerController extends Controller
{
    private $const,$logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();

        $this->logConst = (new logConstants)->getLogConstants();

    }



    public function addBannerImage(Request $request)
    {

        try {


            if ($request->hasFile('imagePath')) {
                $imagePathFile = $request->file('imagePath');
                $imagePath = str_replace(' ', '', $imagePathFile->getClientOriginalName());

                $imagePathFile->move(public_path(env('IMAGE_BANNER_PATH')), $imagePath);
                $fullPath = asset(env('IMAGE_BANNER_PATH')).'/'.$imagePath;
            }

            $imageBanner = imageBanner::create([
                'imagePath' => $fullPath,
                'shortDesc' => $request->shortDesc,
                'Desc' => $request->Desc,
                'status' => 1
            ]);

            if($imageBanner)

                $message = "Banner Image Added by {{username}}";

                $string = str_replace('{{username}}',auth('sanctum')->user()->name, $message);

                DB::table('activity_logs')->insert([
                    'user_id'=>auth('sanctum')->user()->id,
                    'activity'=>$string,
                    'requestResponse'=>json_encode($request->all()),
                ]);
                return (new ResponseHandler)->sendSuccessResponse($this->const['BANNER_IMAGE_ADD'], 200);


        } catch (\Exception $e) {
            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }
    }


    public function editBanner(Request $request, $id)
    {

        try {


            $beforeEdit = imageBanner::where('id', $id)
            ->first()
            ->toArray();

            $imagePathFile = $request->file('imagePath');
            $imagePath =  str_replace(' ', '', $imagePathFile->getClientOriginalName());
            $imagePathFile->move(public_path(env('IMAGE_BANNER_PATH')),$imagePath);
            $fullPath = asset(env('IMAGE_BANNER_PATH')).'/'.$imagePath;

            $updateBanner = imageBanner::where('id', $id)->update([
                'imagePath' => $fullPath,
                'shortDesc' => $request->shortDesc,
                'Desc' => $request->Desc,
                'status' => 1
            ]);

            if ($updateBanner)

                $afterEdit = imageBanner::where('id', $id)
                ->first()
                ->toArray();

                $collection = collect($beforeEdit);

                $diff = collect($afterEdit)->diff($collection);


                $message = "Banner Image Updated by {{username}}";

                $string = str_replace('{{username}}',auth('sanctum')->user()->name, $message);

                DB::table('activity_logs')->insert([
                    'user_id'=>auth('sanctum')->user()->id,
                    'activity'=>$string,
                    'requestResponse'=>json_encode($diff),
                ]);

                return (new ResponseHandler)->sendSuccessResponse($this->const['BANNER_UPDATE'], 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function deleteBanner(Request $request, $id)
    {

        try {

                    $BannerPath = imageBanner::where('id', $id)->first();

                    if(File::exists(public_path(substr($BannerPath->imagePath,22)))){
                        $deleteFile = public_path(substr($BannerPath->imagePath,22));
                        File::delete($deleteFile);
                    }

                    $deleteBanner = imageBanner::where('id', $id)->delete();

            if ($deleteBanner)

                    $message = "Banner Image Deleted by {{username}}";

                    $string = str_replace('{{username}}',auth('sanctum')->user()->name, $message);

                    DB::table('activity_logs')->insert([
                        'user_id'=>auth('sanctum')->user()->id,
                        'activity'=>$string,
                        'requestResponse'=>json_encode($request->all()),
                    ]);

            return (new ResponseHandler)->sendSuccessResponse($this->const['BANNER_DELETE'], 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function getBanner(Request $request)
    {
        try {
            $getBanner = imageBanner::orderBy('id', 'desc')->get();
            if ($getBanner)
                return (new ResponseHandler)->sendSuccessResponse($getBanner, 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function fetchActiveBanner(Request $request)
    {
        try {
            $fetchActiveBanner = imageBanner::orderBy('id', 'desc')->where('status', 1)->get();
            if ($fetchActiveBanner)
                return (new ResponseHandler)->sendSuccessResponse($fetchActiveBanner, 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
                $updateStatus = imageBanner::where('id', $id)->update(['status' => $request->all()[0]]);

                if($updateStatus)

                    $message = "Banner Status Updated by {{username}}";

                    $string = str_replace('{{username}}',auth('sanctum')->user()->name, $message);

                    DB::table('activity_logs')->insert([
                        'user_id'=>auth('sanctum')->user()->id,
                        'activity'=>$string,
                        'requestResponse'=>json_encode($request->all()),
                    ]);

                    return (new ResponseHandler)->sendSuccessResponse($this->const[$request->all()[0] ? 'UPDATE_STATUS_ACTIVE' : 'UPDATE_STATUS_DEACTIVE'], 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function addMidHeader(Request $request){


        try {



            $addMidHeader = DB::table('mid_header_table')->updateOrInsert(
                [
                    'id'=>$request->id
                ],
                [
                    'mid_header'=>$request->mid_header
                ]
            );

            if($addMidHeader)
                return (new ResponseHandler)->sendSuccessResponse($this->const['ADD_MID_HEADER'], 201);



        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }


    }

    public function fetchMidHeader(Request $request){


        try{

            $fetchMidHeader = DB::table('mid_header_table')
            ->orderBy('id','Desc')
            ->select('id','mid_header','status')
            ->first();


            if ($fetchMidHeader)
                return (new ResponseHandler)->sendSuccessResponse($fetchMidHeader, 200);


        }catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function updateMidHeader(Request $request,$id){


            try {
                    $updateMidHeader = DB::table('mid_header_table')
                    ->where('id', $id)
                    ->update(['mid_header' => $request->mid_header]);

                    if($updateMidHeader)
                        return (new ResponseHandler)->sendSuccessResponse($this->const['UPDATE_MID_HEADER'], 201);

            } catch (\Exception $e) {
                return (new ResponseHandler)->sendErrorResponse();
            }

    }


    public function fetchUserMidHeader(Request $request){


        try{

            $fetchMidHeader = DB::table('mid_header_table')
            ->orderBy('id','Desc')
            ->select('id','mid_header','status')
            ->first();


            if ($fetchMidHeader)
                return (new ResponseHandler)->sendSuccessResponse($fetchMidHeader, 200);


        }catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }


}
