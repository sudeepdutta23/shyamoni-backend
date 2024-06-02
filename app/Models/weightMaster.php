<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class weightMaster extends Model
{

    use HasFactory,SoftDeletes;
    protected $table = 'weight_master';
    protected $guarded = [];

}
