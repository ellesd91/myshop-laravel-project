<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    protected $table = 'product_attributes';

    protected $guarded = [];

    public function attribute()
{
    return $this->belongsTo(Attribute::class);
}

public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }

}
