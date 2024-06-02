<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreateShippingOrder extends Model
{
    use HasFactory;

    protected $table = 'create_shipping_orders';
    protected $guarded = [];
}
