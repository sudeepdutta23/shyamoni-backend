<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ithinkOrderFails extends Model
{
    use HasFactory;

    protected $table = 'ithink_order_fail_logs';
    protected $guarded = [];
}
