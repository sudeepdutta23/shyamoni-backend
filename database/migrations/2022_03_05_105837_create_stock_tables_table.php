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
        Schema::create('stock_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('product_id');
            $table->string('product_weight',100)->nullable();
            $table->integer('stock_in')->nullable()->default('0');
            $table->integer('stock_out')->nullable()->default('0');
            $table->integer('stock_status')->nullable();
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
        Schema::dropIfExists('stock_tables');
    }
};
