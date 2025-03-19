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

    public function canceler_info()
    {
        return $this->belongsTo(User::class, 'canceled_by', 'id');
    }

    public function enlister_info()
    {
        return $this->belongsTo(User::class, 'enlisted_by', 'id');
    }

    public function courier_info()
    {
        return $this->belongsTo(Courier::class, 'enterprise', 'id');
    }
}
