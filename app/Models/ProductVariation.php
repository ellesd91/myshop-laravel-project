<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Support\Carbon;
use Carbon\Carbon;


class ProductVariation extends Model
{
    protected $dates = ['deleted_at'];
    protected $table = "product_variations";
    protected $guarded = [];
    protected $appends = ['is_sale'];


     protected $casts = [
        'date_on_sale_from' => 'datetime',
        'date_on_sale_to'   => 'datetime',
    ];




    public function getIsSaleAttribute()
    {
        return ($this->sale_price != null && $this->date_on_sale_from < Carbon::now() && $this->date_on_sale_to > Carbon::now()) ? true : false;
    }
}
