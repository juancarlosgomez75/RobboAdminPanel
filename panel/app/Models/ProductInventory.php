<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductInventory extends Model
{
    protected $table = 'product_inventory';

    public function product_info()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
