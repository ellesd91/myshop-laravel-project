<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
     protected $table = 'attributes';

    protected $guarded =[];

// public function categories()
// {
//     return $this->belongsToMany(Category::class,'attribute_category');
// }

// protected $casts = [
//     'options' => 'array', // اگر ستون options داری و JSON ذخیره می‌کنی
// ];

// public function categories()
// {
//     return $this->belongsToMany(\App\Models\Category::class, 'attribute_category', 'attribute_id', 'category_id');
// }

protected $casts = [
    'options' => 'array', // اگر ستونی به نام options داری (لازم نیست)
];

public function categories()
{
    return $this->belongsToMany(\App\Models\Category::class, 'attribute_category', 'attribute_id', 'category_id')
                ->withPivot('is_variation');
}


}

