<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class productWeight extends Model
{


    use HasFactory,SoftDeletes,Uuids;

    protected $table='product_weight';
    protected $fillable = ['product_id','weight','specialPrice','originalPrice','discountAmount','product_coupon','product_coupons_expiryDate','product_sku'];

    public function product()
    {
        return $this->belongsTo(ProductTable::class);
    }

    public function product_stock(){
        return $this->hasMany(stockTable::class,'product_weight');
    }

    public function product_cart(){
        return $this->hasOne(addtoCart::class,'product_weight');
    }


}
