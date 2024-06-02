<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;


class userFeedback extends Model
{

    use Uuids,HasFactory,SoftDeletes;
    protected $table = 'user_feedback';
    protected $guarded = [];

    public function product_table()
    {
        return $this->belongsTo(userFeedback::class,'product_id');
    }

}
