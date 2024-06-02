<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

use App\Models\ProductImage;
use App\Models\ProductTable;


class addtoCart extends Model
{

    use HasFactory,SoftDeletes,Uuids;

    protected $table='addto_carts';
    protected $guarded = [];

    public function productimage()
    {
        return $this->hasOne(ProductImage::class,'product_id');
    }

    public function product()
    {
        return $this->belongsTo(ProductTable::class);
    }

    public function product_weight(){
        return $this->belongsTo(productWeight::class,'product_weight');
    }


    public function userOrder()
    {
        return $this->belongsTo(orderTable::class);
    }




}
