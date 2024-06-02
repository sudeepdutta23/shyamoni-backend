<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class orderTable extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'order_tables';

    protected $guarded = [];



    public function cartItem()
    {

        return $this->hasMany(addtoCart::class,'user_id');

    }





}
