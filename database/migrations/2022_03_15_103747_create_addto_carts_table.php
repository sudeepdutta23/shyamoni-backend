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
        Schema::create('addto_carts', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('product_id');
            $table->integer('user_id')->nullable();
            $table->bigInteger('pieces')->nullable();
            $table->string('orderID')->nullable();
            $table->string('shyamoni_order_id')->nullable();
            $table->float('product_weight')->nullable();
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
        Schema::dropIfExists('addto_carts');
    }
};
