<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class imageBanner extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'image_banners';
    protected $guarded = [];



}
