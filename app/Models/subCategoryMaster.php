<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class subCategoryMaster extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'sub_category_masters';
    protected $guarded = [];

    public function Category()
    {
        return $this->belongsTo(CategoryMaster::class);
    }


}
