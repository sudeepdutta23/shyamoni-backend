<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    userFeedback,
    Constants
};
use App\Http\Utils\{
    ResponseHandler,
    Authorize
};
use App\Http\Requests\{
    ProductComment
};

use Illuminate\Support\Facades\DB;

class FeedBackController extends Controller
{
    //

    private $const;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    public function addUserFeedBack(ProductComment $request)
    {
        try {

                $addUserFeedBack = DB::table('user_feedback')->insert([
                    'user_name' => empty(auth('sanctum')->user()->id) ? NULL : auth('sanctum')->user()->id,
                    'product_id' => $request->product_id,
                    'userRating' => $request->userRating,
                    'comment' => $request->comment,
                    'status' => 1,
                ]);

                if ($addUserFeedBack)
                    return (new ResponseHandler)->sendSuccessResponse($this->const['ADD_FEEDBACK'], 200);

        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }
    }

    public function getFeedbackByProductID(Request $request, $id)
    {
        try {

            $getFeedbackByProductID=DB::table('user_feedback')
            ->LeftJoin('users','users.id','=','user_feedback.user_name')
            ->where('user_feedback.product_id',$id)
            ->select('user_feedback.product_id','user_feedback.userRating','user_feedback.comment','user_feedback.created_at',DB::Raw("ifnull(users.name, 'Anonymous') as user_name"))
            ->get();

            if ($getFeedbackByProductID)
                return (new ResponseHandler)->sendSuccessResponse($getFeedbackByProductID, 200);
        } catch (\Exception $e) {

            return (new ResponseHandler)->sendErrorResponse();
        }
    }
}
