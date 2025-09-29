<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Models\Brand;
use App\Models\ProductAttribute;
use App\Models\Attribute;
use Illuminate\Support\Carbon;

class Product extends Model
{
     use Sluggable;
    protected $table = 'products';

    protected $guarded =[];
    protected $appends = ['quantity_check', 'sale_check', 'price_check'];


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
    // برای نمایش اتربیوت ها و رابطه یک به چند داریم اینجا در پایین
    public function productAttributes()
{
    return $this->hasMany(ProductAttribute::class);
}

// public function attribute()
// {
//     return $this->belongsTo(Attribute::class);
// }



public function variations()
{
    return $this->hasMany(ProductVariation::class);
}

public function getIsActiveAttribute($value)
    {
        return $value ? 'فعال' : 'غیرفعال';
    }


    public function images()
{
    return $this->hasMany(\App\Models\ProductImage::class);
}




public function getQuantityCheckAttribute()
    {
        return $this->variations()->where('quantity', '>', 0)->first() ?? 0;
    }

public function getSaleCheckAttribute()
    {
        return $this->variations()->where('quantity', '>', 0)->where('sale_price' , '!=' , null)->where('date_on_sale_from', '<', Carbon::now())->where('date_on_sale_to', '>', Carbon::now())->orderBy('sale_price')->first() ?? false;
    }
 public function getPriceCheckAttribute()
    {
        return $this->variations()->where('quantity', '>', 0)->orderBy('price')->first() ?? false;
    }

 public function rates()
    {
        return $this->hasMany(ProductRate::class);
    }




}

