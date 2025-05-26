<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $table = 'requests';

    public function creator_info()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function canceler_info()
    {
        return $this->belongsTo(User::class, 'canceled_by', 'id');
    }
}
