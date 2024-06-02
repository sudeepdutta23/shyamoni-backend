<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    LoginRegisterController,
    CategoryCrudController,
    productController,
    subCategoryController,
    WeightController,
    FeedBackController,
    addToCartController,
    PaymentController,
    AddressDetailController,
    AboutUsController,
    productStockController,
    ImageBannerController,
    shiproketController,
    DeliveryVendorController,
    OrderController,
    ImageUploadController,
    storeMessageController,
    HomeController,
    weightMasterController
};



use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'v1'], function() {


        // Public Routes Start //
            Route::controller(LoginRegisterController::class)->group(function(){

                // For User Register Route
                Route::post("/user/register","register");

                // For Login Route
                Route::post("/user/login","login");

                // For Forget Password
                Route::post("/user/forgotPassword","forgotPassword");

                //For verifyOtp
                Route::post("/user/verifyOtp","verifyOtp");

                Route::post("/user/updatePassword","updatePassword");

            });

            Route::post("/admin/login",[LoginRegisterController::class,"login"]);

            Route::post('/paymentVerification',[PaymentController::class,'paymentVerification'])->name('paymentVerification');


            Route::controller(productController::class)->group(function(){

                // Fetch All Product
                Route::get('/fetchProduct','fetchProduct');

                // Fetch Random Products
                Route::get('/randomFetchProduct','randomFetchProduct');

                  // Fetch Single Product By ID
                Route::get('/fetchSingleProduct/{id}','fetchSingleProduct');

                //  searchProduct
                Route::get('/searchProduct','searchProduct');

                // Search Product Under Category
                Route::get('/getProductUnderCategory/{id}','getProductUnderCategory');

                Route::get('/getProductUnderSubCategory/{id}','getProductUnderSubCategory');

                // Route::get('/filterProduct','filterProduct');

                Route::get('/catSubCatFetch','catSubCatFetch');


            });

            // userFeedback
            Route::controller(FeedBackController::class)->group(function(){
                //  Add User Feedback
                Route::post('/addUserFeedBack','addUserFeedBack');
                // Get User feedback By Product ID
                Route::get('/getFeedbackByProductID/{id}','getFeedbackByProductID');

            });
            // userFeedback

            // Category CRUD
            Route::controller(CategoryCrudController::class)->group(function(){

                // Fetch All Category
                Route::get('/fetchCategory','fetchCategory');

            });

            // Category CRUD end

            Route::get('/fetchState',[AddressDetailController::class,'fetchState'])->name('fetchState');

            Route::get('/fetchCity/{state_id}',[AddressDetailController::class,'fetchCity'])->name('fetchCity');

            Route::get('/getAbout',[AboutUsController::class,'getAbout'])->name('getAbout');

            Route::post('/sendContactMail',[LoginRegisterController::class,'sendContactMail'])->name('sendContactMail');

            Route::controller(CategoryCrudController::class)->group(function(){
                // Get Category by ID
                Route::get('/getCategoryByID/{id}','getCategoryByID');
            });

            Route::controller(subCategoryController::class)->group(function(){
                // get Sub Category by ID
                Route::get('/getSubCategoryByID/{id}','getSubCategoryByID');
            });

            Route::get("/fetchActiveBanner",[ImageBannerController::class,'fetchActiveBanner'])->name('fetchActiveBanner');

            // CheckAdmin Route
            Route::get('/checkAdmin',[LoginRegisterController::class,'checkAdmin'])->name('checkAdmin');

            Route::get('/fetchUserMidHeader',[ImageBannerController::class,'fetchUserMidHeader'])->name('fetchUserMidHeader');



        // Public Routes End //

        // Protected Routes start
            Route::group(['prefix' => 'user','middleware'=>'userAuth'],function(){

                Route::post('/profileUpload',[ImageUploadController::class,'profileUpload'])->name('profileUpload');

                Route::controller(LoginRegisterController::class)->group(function(){
                    Route::post('/updateProfileInfo','updateProfileInfo')->name('updateProfileInfo');
                    Route::get('/checkUser','checkUser')->name('checkUser');
                    Route::post("/logout","userLogout");
                });

                Route::controller(AddressDetailController::class)->group(function(){
                    Route::get('/getAddress','getAddress')->name('getAddress');
                    Route::post('/addAddress','addAddress')->name('addAddress');
                    Route::post('/editAddress/{id}','editAddress')->name('editAddress');
                    Route::delete('/deleteAddress/{id}','deleteAddress')->name('deleteAddress');
                    Route::post('/editProfile','editProfile')->name('editProfile');
                    Route::get('/getProfileDetails','getProfileDetails')->name('getProfileDetails');
                });

                // addToCart Routes

                Route::controller(addToCartController::class)->group(function(){
                    Route::get('/fetchCartItem','fetchCartItem');
                    Route::post('/addCartItem','addCartItem');
                    Route::post('/editCartItem/{id}','editCartItem');
                    Route::delete('/deleteCartItem/{id}','deleteCartItem');

                    // Route for Cart Item Count
                    Route::get('/cartItemCount','cartItemCount');
                });

                Route::post('/payment',[PaymentController::class,'payment'])->name('payment');

                Route::post('/paymentSuccess',[PaymentController::class,'paymentSuccess'])->name('paymentSuccess');

                Route::get('/InvoicePage/{shyamoni_order_id}',[PaymentController::class,'InvoicePage'])->name('InvoicePage');

                // Route::post('/getPaymentStatus',[PaymentController::class,'getPaymentStatus'])->name('getPaymentStatus');

                Route::get('/reloadApi',[PaymentController::class,'reloadApi'])->name('reloadApi');

                Route::get('/InvoicePage/{shyamoni_order_id}',[PaymentController::class,'InvoicePage'])->name('InvoicePage');



                // addToCart Routes End

                // Get Customer Order Details API
                Route::get('/getAllOrders',[OrderController::class,'getAllOrders'])->name('getAllOrders');

                Route::get('/getOrderDetailsByID/{id}',[OrderController::class,'getOrderDetailsByID'])->name('getOrderDetailsByID');

                Route::post('/trackOrder/{awb_id}',[OrderController::class,'trackOrder'])->name('trackOrder');

                Route::post('/cancelOrder/{orderid}',[OrderController::class,'cancelOrder'])->name('cancelOrder');

                Route::post('/checkPinCode',[OrderController::class,'checkPinCode'])->name('checkPinCode');

                Route::get('/getGST',[productController::class,'getGST'])->name('getGST');

            });
        // Protected Routes end

        // Public Routes

         // get City and State
         Route::post('/getState',[OrderController::class,'getState'])->name('getState');
         Route::post('/getCity',[OrderController::class,'getCity'])->name('getCity');

});


    Route::group(['prefix' => 'v1/admin','middleware' => 'auth:sanctum','middleware'=>'check_admin_token'], function() {



        // $admin_role = explode(",",env('ADMIN_ROLE'));

        // if(!empty(auth('sanctum')->user()->role_id) && in_array((auth('sanctum')->user()->role_id),$admin_role))
        // {

                        // updateUserProfile Api start

                                Route::controller(LoginRegisterController::class)->group(function(){

                                    Route::post('/updateUserProfileInfo/{user_id}','updateUserProfileInfo');
                                    Route::get('/getAllUser','getAllUser');
                                    Route::get('/getUserByID/{user_id}','getUserByID');


                                });

                     // dashboardData

                                Route::controller(HomeController::class)->group(function(){
                                    Route::get('/getDashboardData','getDashboardData');
                                });




                        // updateUserProfile Api start


                        Route::controller(storeMessageController::class)->group(function(){

                            Route::post('/addMailMessage','addMailMessage');

                            Route::put('/editMailMessage/{id}','editMailMessage');

                            Route::get('/getAllMailMessage','getAllMailMessage');

                            Route::delete('/deleteMailMessage/{id}','deleteMailMessage');

                        });

                    // Routes For Admin //
                        //Controller Route Group

                        // Category CRUD
                        Route::controller(CategoryCrudController::class)->group(function(){

                            // Fetch All Category
                            Route::get('/fetchCategory','fetchCategory');

                            //  Add Category
                            Route::post('/addCategory','addCategory');

                            // Edit Category
                            Route::post('/editCategory/{id}','editCategory');

                            Route::delete('/deleteCategory/{id}','deleteCategory');

                            // Get Category by ID
                            Route::get('/getCategoryByID/{id}','getCategoryByID');

                        });
                        // Category CRUD end

                        // SubCategory CRUD
                        Route::controller(subCategoryController::class)->group(function(){

                            // fetch SubCategory
                            Route::get('/fetchSubCategory','fetchSubCategory');

                            //  Add SubCategory
                            Route::post('/addSubCategory','addSubCategory');

                            // Edit SubCategory
                            Route::post('/editSubCategory/{id}','editSubCategory');

                            // delete SubCategory
                            Route::delete('/deleteSubCategory/{id}','deleteSubCategory');

                            // Get Sub Category by ID
                            Route::get('/getSubCategoryByID/{id}','getSubCategoryByID');

                            // Fetch SubCategory By ID
                            Route::post('/SubCategoryByID/{id}','SubCategoryByID');

                            // Fetch SubCategory Under Category
                            Route::post('/getCatSubCategory','getCatSubCategory');

                            // get Sub Category by ID
                            Route::get('/getSubCategoryByID/{id}','getSubCategoryByID');


                        });
                        // SubCategory CRUD end

                        // Product CRUD
                        Route::controller(productController::class)->group(function(){

                            // Fetch All Product
                            Route::get('/fetchProduct','fetchProduct');

                            // Fetch Single Product By ID
                            Route::get('/fetchSingleProduct/{id}','fetchSingleProduct');

                            // Fetch Product Under Category By ID
                            Route::post('/ProductUnderCategory/{id}','ProductUnderCategory');

                            // Fetch Product Under SubCategory By ID
                            Route::post('/ProductUnderSubCategory/{id}','ProductUnderSubCategory');

                            // Fetch Random Products
                            Route::get('/randomFetchProduct','randomFetchProduct');

                            //  Add Product
                            Route::post('/addProduct','addProduct');

                            // Edit Product
                            Route::post('/editProduct/{id}','editProduct');

                            // Update Product_Weight
                            Route::post('/editProductWeight/{product_id}','editProductWeight');

                            // getWeightByProduct
                            Route::get('/getWeightByProduct/{product_id}','getWeightByProduct');

                            // Fetch All Weight
                            Route::get('/fetchAllWeight','fetchAllWeight');

                            // Delete Product Weight
                            Route::delete('/deleteProductWeight/{id}','deleteProductWeight');

                            // Delete
                            Route::delete('/deleteProduct/{id}','deleteProduct');

                            // Delete Product Image
                            Route::delete('/deleteProductImage/{id}','deleteProductImage');

                            // get Product Image Under that Product
                            Route::get('/getImagesByProductID/{product_id}','getImagesByProductID');

                            Route::get('/getAllProductForStock','getAllProductForStock');

                            Route::delete('/deleteTags/{id}','deleteTags');

                            Route::post('/addIndividualTag/{product_id}','addIndividualTag');

                            Route::get('/getTagsByProductID/{product_id}','getTagsByProductID');

                            Route::put('/updateProductStatus/{product_id}','updateProductStatus');

                            Route::get('/getGST','getGST');

                        });
                        // Product CRUD


                        Route::controller(WeightController::class)->group(function(){

                            // Fetch Weight
                            // Route::post('/fetchProductWeight','fetchProductWeight');

                            // //  Add Weight
                            // Route::post('/addProductWeight','addProductWeight');

                            // // Edit Weight
                            // Route::put('/editProductWeight/{id}','editProductWeight');

                            // // Delete Weight
                            // Route::delete('/deleteProductWeight/{id}','deleteProductWeight');

                        });


                        // Weight CRUD start

                        Route::controller(weightMasterController::class)->group(function(){

                            // Fetch Weight
                            Route::get('/getWeightMaster','getWeightMaster');

                            // //  Add Weight
                            Route::post('/addWeightMaster','addWeightMaster');

                            // // Edit Weight
                            Route::put('/editWeightMaster/{id}','editWeightMaster');

                            // // Delete Weight
                            Route::delete('/deleteWeightMaster/{id}','deleteWeightMaster');

                        });
                        // Weight CRUD


                        // Weight CRUD end

                    // Stock CRUD
                        Route::controller(productStockController::class)->group(function(){

                            //  Add Stock
                            Route::post('/addProductStock','addProductStock');

                            // Fetch Stock
                            Route::get('/fetchProductStock','fetchProductStock');

                            // Stock Details by Product ID
                            Route::get('/getStockByProduct/{product_id}','getStockByProduct');


                            // Edit Stock
                            // Route::put('/editProductStock/{id}','editProductStock');

                            // Delete Stock
                            // Route::delete('/deleteProductStock/{id}','deleteProductStock');

                        });
                    // Stock CRUD

                        // About CRUD
                        Route::controller(AboutUsController::class)->group(function(){

                            // For About Us Route
                            Route::post("/addAbout","addAbout");
                            Route::put("/editAbout/{id}","editAbout");
                            Route::delete("/deleteAbout/{id}","deleteAbout");
                            Route::get("/getAbout","getAbout");

                        });

                        Route::controller(ImageBannerController::class)->group(function(){
                            Route::post("/addBannerImage","addBannerImage");
                            Route::post("/editBanner/{id}","editBanner");
                            Route::delete("/deleteBanner/{id}","deleteBanner");
                            Route::get("/getBanner","getBanner");
                            Route::put("/updateStatus/{id}","updateStatus");

                            Route::post("/addMidHeader","addMidHeader");
                            Route::get("/fetchMidHeader","fetchMidHeader");
                            Route::post("/updateMidHeader/{id}","updateMidHeader");

                        });


                        Route::get('/getOrderDetailsByID/{id}',[OrderController::class,'getOrderDetailsByID'])->name('getOrderDetailsByID');


                        // Update AWB Route
                        Route::put('/updateAWB',[OrderController::class,'updateAWB'])->name('updateAWB');

                        Route::post('/trackOrder/{awb_id}',[OrderController::class,'trackOrder'])->name('trackOrder');

                        // Get all Orders
                        Route::get('/fetchAllOrders',[OrderController::class,'fetchAllOrders'])->name('fetchAllOrders');

                        // Fetch All Admin Activity Log Details
                        Route::get("/fetchActivityLog",[LoginRegisterController::class,"fetchActivityLog"]);

                        // Logout Route
                        Route::post("/logout",[LoginRegisterController::class,"adminLogout"]);

            // Routes For Admin End //

        // }
    });



