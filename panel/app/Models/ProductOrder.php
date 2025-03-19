<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    protected $table = 'product_orders';

    public function creator_info()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function preparator_info()
    {
        return $this->belongsTo(User::class, 'prepared_by', 'id');
    }

    public function sender_info()
    {
        return $this->belongsTo(User::class, 'sended_by', 'id');
    }
}
