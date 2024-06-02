<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_weight', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('product_id');
            $table->string('weight')->nullable();
            $table->float('specialPrice')->nullable();
            $table->float('originalPrice')->nullable();
            $table->float('discountAmount')->nullable();
            $table->string('product_coupon')->nullable();
            $table->date('product_coupons_expiryDate')->nullable();
            $table->string('product_sku')->nullable();
            $table->float('deliveryCharge')->nullable();
            $table->float('gst')->nullable();
            $table->softDeletes();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_weights');
    }
};
