<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';

    protected $guarded =[];


    public function products()
{
    return $this->belongsToMany(Product::class, 'product_tag');
}
}
