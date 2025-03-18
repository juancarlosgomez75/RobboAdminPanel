<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    public function category_info()
    {
        return $this->belongsTo(ProductCategory::class, 'category', 'id');
    }

    public function inventory(){
        return $this->hasOne(ProductInventory::class, 'product_id');
    }
}
