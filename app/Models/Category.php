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
    // اگر نام جدول pivot شما چیز دیگری است (مثلاً category_attribute)
    // دومین آرگومان را همان نام بگذارید:
    // return $this->belongsToMany(\App\Models\Attribute::class, 'category_attribute', 'category_id', 'attribute_id');

    return $this->belongsToMany(\App\Models\Attribute::class, 'attribute_category', 'category_id', 'attribute_id');
}




}

