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
        Schema::create('product_table', function (Blueprint $table) {
            $table->uuid('id');
            $table->integer('cate_id')->nullable();
            $table->integer('subCate_id')->nullable();
            $table->string('productName')->nullable();
            $table->string('brand')->nullable();
            $table->text('shortDesc')->nullable();
            $table->text('longDesc')->nullable();
            $table->string('keywords')->nullable();
            $table->integer('product_status')->nullable()->default('1');
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
        Schema::dropIfExists('product_masters');
    }
};
