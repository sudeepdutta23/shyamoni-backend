<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Utils\{
    ResponseHandler
};

use App\Models\{
     weightMaster,
     Constants
};


use App\Http\Requests\{
    weight,
};




class weightMasterController extends Controller
{

    private $const;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    public function getWeightMaster(Request $request)
    {
        try {

            $fetchAllWeight = weightMaster::orderBy('id', 'asc')
            ->select('id','weight','deliveryCharge')
            ->get();

            return (new ResponseHandler)->sendSuccessResponse($fetchAllWeight, 200);

        } catch (\Exception $e) {

            dd($e);
            return (new ResponseHandler)->sendErrorResponse();
        }
    }


    public function addWeightMaster(Request $request)
    {
        try {


            $checkWeight = weightMaster::where(['weight' => $request->weight])
            ->select('weight')
            ->groupBy('weight')
            ->first();

            if(!empty($checkWeight)){

                if($checkWeight->weight == $request->weight)
                    return (new ResponseHandler)->sendSuccessResponse($this->const['MASTER_WEIGHT_EXISTS'], 500);

            }else
            {

                $addWeightMaster = weightMaster::create([
                    'weight' => $request->weight,
                    'deliveryCharge' => $request->deliveryCharge,
                ]);

                if ($addWeightMaster)
                    return (new ResponseHandler)->sendSuccessResponse($this->const['MASTER_WEIGHT_ADD'], 200);

            }


        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function editWeightMaster(Request $request, $id)
    {
        try {


            $checkWeight = weightMaster::where(['weight' => $request->weight])
            ->select('weight')
            ->groupBy('weight')
            ->first();

            if(!empty($checkWeight)){

                if($checkWeight->weight == $request->weight)
                    return (new ResponseHandler)->sendSuccessResponse($this->const['MASTER_WEIGHT_EXISTS'], 500);

            }else
            {

                $editWeightMaster = weightMaster::where('id', $id)->update([
                    'weight' => $request->weight,
                    'deliveryCharge' => $request->deliveryCharge,
                ]);
                if ($editWeightMaster)
                    return (new ResponseHandler)->sendSuccessResponse($this->const['MASTER_WEIGHT_UPDATE'], 200);

            }

        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }


    public function deleteWeightMaster($id)
    {
        try {

            $deleteWeightMaster = weightMaster::find($id)->delete();
            if ($deleteWeightMaster)
                return (new ResponseHandler)->sendSuccessResponse($this->const['MASTER_WEIGHT_DELETE'], 200);
        } catch (\Exception $e) {
            return (new ResponseHandler)->sendErrorResponse();
        }
    }


}
