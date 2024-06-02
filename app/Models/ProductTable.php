<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class ProductTable extends Model
{
    use Uuids;
    use HasFactory,SoftDeletes;

    protected $table='product_table';
    protected $guarded=[];

    public function product_tags(){
        return $this->hasMany(productTags::class,'product_id');
    }

    public function product_image()
    {
        return $this->hasOne(ProductImage::class,'product_id');
    }

    public function productImage()
    {
        return $this->hasMany(ProductImage::class,'product_id');
    }

    public function userFeedBack()
    {
        return $this->hasOne(userFeedback::class,'product_id');
    }

    public function cartItem()
    {
        return $this->hasMany(addtoCart::class,'product_id');
    }


    public function productStock()
    {
        return $this->hasMany(stockTable::class,'product_id');
    }

    // public function productWeight(){
    //     return $this->hasMany(productWeight::class,'product_id');
    // }

    public function product_weight(){
        return $this->hasMany(productWeight::class,'product_id','id');
    }

    public function subCategory(){
        return $this->belongsTo(subCategoryMaster::class);
    }


    public function Category(){
        return $this->belongsTo(CategoryMaster::class);
    }






}
