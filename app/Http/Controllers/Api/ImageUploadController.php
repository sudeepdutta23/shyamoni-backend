<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\{
    User,
    Constants

};
use App\Http\Utils\{
    ResponseHandler
};

class ImageUploadController extends Controller
{

    private $const;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }


    public function profileUpload(Request $request){
        try {


            if($request->hasFile('image')){

                $userPhotoFile = $request->file('image');
                $userPhoto = $userPhotoFile->getClientOriginalName();
                $userPhotoFile->move(public_path(env('USER_PHOTO_PATH')),$userPhoto);
                $userPhotoPath = asset(env('USER_PHOTO_PATH')).'/'.$userPhoto;

                $updateProfileInfo = User::where('id', $request->userID)
                ->update([
                    'userPhoto' => $userPhotoPath,
                ]);

                if ($updateProfileInfo)
                    return (new ResponseHandler)->sendSuccessResponse($this->const['PROFILE_PHOTO_UPDATE'], 200);
            }

        } catch (\Exception $e) {

            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }
    }
}
