<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CategoryMaster extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'category_masters';
    protected $guarded = [];

    public function AllsubCategory()
    {
        return $this->hasMany(subCategoryMaster::class,'category_id');
    }

}
