<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class MachineHistory extends Model
{
    protected $table = 'machine_history';

    public function author_info()
    {
        return $this->belongsTo(User::class, 'author', 'id');
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => mb_strtoupper($value, 'UTF-8'),
            get: fn ($value) => mb_strtoupper($value, 'UTF-8')

        );
    }
}
