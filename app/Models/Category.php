<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    protected $table = 'categories';

    protected $guarded =[];

    public function getIsActiveAttribute($is_active)
    {
    return $is_active ? 'فعال' : 'غیرفعال';
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }



    public function attributes()
{


    return $this->belongsToMany(\App\Models\Attribute::class, 'attribute_category', 'category_id', 'attribute_id');
}

public function products()
    {
        return $this->hasMany(Product::class);
    }




}

