<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Models\Brand;

class Product extends Model
{
     use Sluggable;
    protected $table = 'products';

    protected $guarded =[];


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }
    //برای رابطه زیر چون ادرس دادن کامل استفاده کردیم دیگه لازم نیست در بالای النجا چیزی از کتگوری یوز کنیم
    public function category() {
        return $this->belongsTo(\App\Models\Category::class);
    }
}

