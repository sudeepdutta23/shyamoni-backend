<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Utils\{
    ResponseHandler
};
use App\Models\{
    addtoCart,
    CategoryMaster,
    subCategoryMaster,
    ProductTable,
    orderTable,
    User

};
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //

    public function show()
	{
	    // only get meta tags if we're actually
        // rendering homepage and not a fallback route
        return view('welcome');

	}

    public function getDashboardData(Request $request){
        try{
        $res['activeCartData'] = addtoCart::orderBy('id', 'asc')->get()->count();
        $res['inActiveCartData'] = addtoCart::onlyTrashed()->orderBy('id', 'asc')->get()->count();
        $res['activeCategory'] = CategoryMaster::orderBy('id', 'asc')->get()->count();
        $res['inActiveCategory'] = CategoryMaster::onlyTrashed()->orderBy('id', 'asc')->get()->count();
        $res['activeSubCategory'] = subCategoryMaster::orderBy('id', 'asc')->get()->count();
        $res['inActiveSubCategory'] = subCategoryMaster::onlyTrashed()->orderBy('id', 'asc')->get()->count();
        $res['activeProduct'] = ProductTable::orderBy('id', 'asc')->get()->count();
        $res['inActiveProduct'] = ProductTable::onlyTrashed()->orderBy('id', 'asc')->get()->count();
        $res['orderCreated'] = orderTable::withTrashed()->orderBy('id', 'asc')->whereNotNull('razorpay_paymentID')->get()->count();
        $res['orderFailed'] = orderTable::withTrashed()->orderBy('id', 'asc')->whereNull('razorpay_paymentID')->get()->count();
        $res['user'] = User::orderBy('id', 'asc')->where('role_id','=',2)->get()->count();
        $res['admin'] = User::orderBy('id', 'asc')->where('role_id','=',1)->get()->count();
        return (new ResponseHandler)->sendSuccessResponse($res,200);
        }catch(\Exception $e){
            return (new ResponseHandler)->sendErrorResponse();
        }

    }


}
