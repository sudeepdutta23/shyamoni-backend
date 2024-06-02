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
        Schema::create('create_shipping_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->nullable();
            $table->string('shyamoni_order_id')->nullable();
            $table->integer('shipment_id')->nullable();
            $table->string('status')->nullable();
            $table->integer('status_code')->nullable();
            $table->integer('onboarding_completed_now')->nullable();
            $table->string('awb_code')->nullable();
            $table->string('courier_company_id')->nullable();
            $table->string('courier_name')->nullable();
            $table->text('response')->nullable();
            $table->integer('userID')->nullable();
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
        Schema::dropIfExists('create_shipping_orders');
    }
};
