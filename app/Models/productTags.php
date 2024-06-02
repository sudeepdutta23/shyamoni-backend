<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class productTags extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'tags';
    protected $guarded = [];

    public function product_table()
    {
        return $this->belongsTo(ProductTable::class);
    }
}
