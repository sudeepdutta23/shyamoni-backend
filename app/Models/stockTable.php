<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;


class stockTable extends Model
{
    use HasFactory,SoftDeletes,Uuids;

    protected $table='stock_tables';
    protected $guarded=[];

    public function product()
    {
        return $this->belongsTo(ProductTable::class,'product_id');
    }

    public function product_weight()
    {
        return $this->belongsTo(productWeight::class,'product_id');
    }






}
