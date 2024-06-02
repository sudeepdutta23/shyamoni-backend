<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;



class ProductImage extends Model
{
    use Uuids;
    use HasFactory,SoftDeletes;

    protected $table = 'productimages';

    protected $guarded=[];


    public function product_table()
    {
        return $this->belongsTo(ProductTable::class);
    }

    public function cartItem()
    {
        return $this->belongsTo(ProductTable::class);

    }
}
