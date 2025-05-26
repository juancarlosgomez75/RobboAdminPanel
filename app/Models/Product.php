<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    //Mutadores
    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => mb_strtoupper($value, 'UTF-8'),
            get: fn ($value) => mb_strtoupper($value, 'UTF-8')
        );
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => mb_strtoupper($value, 'UTF-8'),
            get: fn ($value) => mb_strtoupper($value, 'UTF-8')

        );
    }

}
