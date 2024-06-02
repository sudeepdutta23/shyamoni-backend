<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Constants,
    StateMaster,
    userAddress,
    City,
    User
};
use App\Http\Utils\{
    ResponseHandler
};
use Illuminate\Support\Facades\DB;
use App\Http\Requests\{
    Address,
};

class AddressDetailController extends Controller
{

    private $const;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    public function fetchState(Request $request)
    {
        try {
            $fetchState = StateMaster::orderBy('id', 'asc')->select('id','state_name')->get();

            if ($fetchState)
                return (new ResponseHandler)->sendSuccessResponse($fetchState, 200);
        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function fetchCity(Request $request,$state_id)
    {

        try {

            $fetchCity = City::orderBy('id', 'asc')
            ->where('state_id',$state_id)
            ->select('id','city_name')
            ->get();

            if ($fetchCity)
                return (new ResponseHandler)->sendSuccessResponse($fetchCity, 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function addAddress(Address $request)
    {
        try {

            $checkAddress = userAddress::where('user_id', $request->userID)->get();
            if ($checkAddress->count() > env("MAX_ADDRESS_ALLOWED"))
                return (new ResponseHandler)->sendErrorResponse($this->const['ADDRESS_CHECK'], 409);

            $addAddress = userAddress::create([
                'user_id' => $request->userID,
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'city' => $request->cityID,
                'district' => $request->district,
                'state' => $request->stateID,
                'country' => $request->country,
                'zip' => $request->zip,
                'status' => 1,
            ]);

            if ($addAddress)

                return (new ResponseHandler)->sendSuccessResponse($this->const['USER_ADDRESS_ADD'], 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function editAddress(Address $request,$id)
    {
        try {

            $updateAddress = userAddress::where('id', $id)->update([
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'city' => $request->cityID,
                'district' => $request->district,
                'state' => $request->stateID,
                'country' => $request->country,
                'zip' => $request->zip,
                'status' => 1,
            ]);

            if ($updateAddress)

                return (new ResponseHandler)->sendSuccessResponse($this->const['USER_ADDRESS_UPDATE'], 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function deleteAddress(Request $request, $id)
    {
        try {

            $deleteAddress = userAddress::where('user_id', $request->userID)->where('id', $id)->delete();

            if ($deleteAddress)

                return (new ResponseHandler)->sendSuccessResponse($this->const['USER_ADDRESS_DELETE'], 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function getAddress(Request $request)
    {
        try {
            $getAddress = DB::table('user_addresses')
            ->where('user_addresses.user_id', $request->userID)
            ->orderBy('user_addresses.id', 'asc')
            ->join('statemaster','statemaster.id','=','user_addresses.state')
            ->join('citymaster','citymaster.id','=','user_addresses.city')
            ->select('user_addresses.id','user_addresses.user_id','user_addresses.address_line_1','user_addresses.address_line_2','user_addresses.district',
        'user_addresses.country','user_addresses.zip','user_addresses.status','statemaster.id as stateID','statemaster.state_name as state','citymaster.city_name as city','citymaster.id as cityID')
            ->where('user_addresses.deleted_at','=',NULL)
            ->get();

            if ($getAddress)
                return (new ResponseHandler)->sendSuccessResponse($getAddress, 200);
        } catch (\Exception $e) {
            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function editProfile(Request $request)
    {

        try {

            if($request->hasFile('userPhoto')) {

                $image = $request->file('userPhoto');
                $image_new_name = $image->getClientOriginalName();
                $image->move(public_path(env('USER_PHOTO_PATH').'/'.$request->userID), $image_new_name);
                $imagePath =  asset(env('USER_PHOTO_PATH').'/'.$request->userID).'/'.$image_new_name;

                $editProfile = User::where('id', $request->userID)->update([
                    'phoneNo' => $request->phoneNo,
                    'userPhoto' => $imagePath,
                    'gender' => $request->gender,
                    'dob' => $request->dob,
                    'name' => $request->name,
                    'email' => $request->email
                ]);
            } else {

                $editProfile = User::where('id', $request->userID)->update([
                    'phoneNo' => $request->phoneNo,
                    'gender' => $request->gender,
                    'dob' => $request->dob,
                    'name' => $request->name,
                    'email' => $request->email
                ]);
            }


            if ($editProfile)

                return (new ResponseHandler)->sendSuccessResponse($this->const['EDIT_PROFILE'], 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function getProfileDetails(Request $request)
    {
        try {
            $getProfileDetails = User::where('id', $request->userID)->select('name','email','phoneNo','userPhoto','gender','dob')->get();
            if ($getProfileDetails)
                return (new ResponseHandler)->sendSuccessResponse($getProfileDetails, 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }

}
