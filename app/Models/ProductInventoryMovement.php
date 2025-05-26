<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductInventoryMovement extends Model
{
    protected $table = 'product_inventory_movements';

    public function inventory_info()
    {
        return $this->belongsTo(ProductInventory::class, 'inventory_id', 'id');
    }

    public function author_info()
    {
        return $this->belongsTo(User::class, 'author', 'id');
    }
}
