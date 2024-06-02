<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Constants,
    logConstants,
    AboutUs
};
use Illuminate\Support\Facades\DB;
use App\Http\Utils\{
    ResponseHandler
};

use App\Http\Requests\{
    About,
};

use Illuminate\Support\Facades\Auth;


class AboutUsController extends Controller
{
    private $const,$logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();

        $this->logConst = (new logConstants)->getLogConstants();

    }

    public function getAbout(){
        try{
            $getAbout = AboutUs::first();
            if($getAbout)
                return (new ResponseHandler)->sendSuccessResponse($getAbout, 200);
        }
        catch(\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function addAbout(About $request){

        try{

            $addAbout = AboutUs::create([
                'about'=>$request->about,
                'status'=>1
            ]);

            if($addAbout){

                $message = "About Content Added by {{username}}";

                $string = str_replace('{{username}}',auth('sanctum')->user()->name, $message);

                DB::table('activity_logs')->insert([
                    'user_id'=>auth('sanctum')->user()->id,
                    'activity'=>$string,
                    'requestResponse'=>json_encode($request->all()),
                ]);
                return (new ResponseHandler)->sendSuccessResponse($this->const['ADD_ABOUT'], 201);
            }

        }
        catch(\Exception $e)
        {
            return (new ResponseHandler)->sendErrorResponse();

        }
    }

    public function editAbout(About $request,$id){

        try{

            $beforeEdit = AboutUs::where('id',$id)
            ->first()
            ->toArray();

            $editAbout = AboutUs::where('id',$id)->update([
                'about'=>$request->about
            ]);

            if($editAbout){


                $afterEdit = AboutUs::where('id',$id)
                ->first()
                ->toArray();

                $collection = collect($beforeEdit);

                $diff = collect($afterEdit)->diff($collection);

                $message = "About Content Updated by {{username}}";

                $string = str_replace('{{username}}',auth('sanctum')->user()->name, $message);

                DB::table('activity_logs')->insert([
                    'user_id'=>auth('sanctum')->user()->id,
                    'activity'=>$string,
                    'requestResponse'=>json_encode($diff),
                ]);
                return (new ResponseHandler)->sendSuccessResponse($this->const['UPDATE_ABOUT'], 201);
            }

        }
        catch(\Exception $e)
        {
            return (new ResponseHandler)->sendErrorResponse();

        }
    }

    public function deleteAbout(Request $request,$id){

        try{

            $deleteAbout = AboutUs::where('id',$id)->delete();

            if($deleteAbout){

                $message = "About Content Deleted by {{username}}";

                $string = str_replace('{{username}}',auth('sanctum')->user()->name, $message);

                DB::table('activity_logs')->insert([
                    'user_id'=>auth('sanctum')->user()->id,
                    'activity'=>$string,
                    'requestResponse'=>json_encode($request->all()),
                ]);

                return (new ResponseHandler)->sendSuccessResponse($this->const['DELETE_ABOUT'], 200);
            }

        }
        catch(\Exception $e)
        {
            return (new ResponseHandler)->sendErrorResponse();

        }
    }
}
