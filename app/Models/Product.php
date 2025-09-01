<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

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
}

