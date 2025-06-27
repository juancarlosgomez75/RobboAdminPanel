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

    public function receiver_info()
    {
        return $this->belongsTo(User::class, 'received_by', 'id');
    }

    public function finisher_info()
    {
        return $this->belongsTo(User::class, 'finished_by', 'id');
    }

    public function collection_reason_info()
    {
        return $this->belongsTo(CollectionReason::class, 'collection_reason', 'id');
    }

    public function courier_info()
    {
        return $this->belongsTo(Courier::class, 'enterprise', 'id');
    }
}
