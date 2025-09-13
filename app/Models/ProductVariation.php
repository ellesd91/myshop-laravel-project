<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariation extends Model
{
    protected $dates = ['deleted_at'];
    protected $table = "product_variations";
    protected $guarded = [];
}
