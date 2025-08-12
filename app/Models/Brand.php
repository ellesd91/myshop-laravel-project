<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use Sluggable , HasFactory;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected $table = 'brands';

    protected $guarded =[];
    public function getIsActiveAttribute($is_active)
{
    return $is_active ? 'فعال' : 'غیرفعال';
}

}
