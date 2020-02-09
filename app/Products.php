<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $guarded = [];

    public function images()
    {
        return $this->hasMany('App\ProductPhotos', 'product_id');
    }
}
