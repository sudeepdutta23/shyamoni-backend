<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

Route::get('{all}',[HomeController::class,'show'])->where('all', '.*');



