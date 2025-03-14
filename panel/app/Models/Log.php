<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';

    public function author_info()
    {
        return $this->belongsTo(User::class, 'author', 'id'); // 'rank' es la clave foránea en users, 'id' es la clave primaria en ranks
    }
}
