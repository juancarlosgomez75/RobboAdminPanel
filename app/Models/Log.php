<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Log extends Model
{
    protected $table = 'logs';

    public function author_info()
    {
        return $this->belongsTo(User::class, 'author', 'id'); // 'rank' es la clave foránea en users, 'id' es la clave primaria en ranks
    }

    protected function menu(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => mb_strtoupper($value, 'UTF-8'),
            get: fn ($value) => mb_strtoupper($value, 'UTF-8')

        );
    }

    protected function section(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => mb_strtoupper($value, 'UTF-8'),
            get: fn ($value) => mb_strtoupper($value, 'UTF-8')

        );
    }

    protected function action(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => mb_strtoupper($value, 'UTF-8'),
            get: fn ($value) => mb_strtoupper($value, 'UTF-8')

        );
    }

}
