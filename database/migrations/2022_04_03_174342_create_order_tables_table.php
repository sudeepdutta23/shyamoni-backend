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
        Schema::create('order_tables', function (Blueprint $table) {
            $table->id();
            $table->float('totalPrice')->nullable();
            $table->string('razorpay_order_id')->nullable();
            $table->string('shyamoni_order_id')->nullable();
            $table->string('receiptId')->nullable();
            $table->integer('user_id')->nullable();
            $table->date('orderDate')->nullable();
            $table->string('razorpay_paymentID')->nullable();
            $table->string('paymentDone')->nullable();
            $table->string('address_id')->nullable();
            $table->bigInteger('awb_id')->nullable();
            $table->float('product_weight')->nullable();
            $table->bigInteger('is_local')->nullable();
            $table->tinyInteger('is_cancel')->nullable()->default('0');;
            $table->bigInteger('paymentResponse')->nullable();
            $table->string('refund_id')->nullable();
            $table->string('deliveryCharge',100)->nullable();
            $table->integer('status')->nullable()->default('1');
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
        Schema::dropIfExists('order_tables');
    }
};
