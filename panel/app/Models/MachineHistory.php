<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MachineHistory extends Model
{
    protected $table = 'machine_history';

    public function author_info()
    {
        return $this->belongsTo(User::class, 'author', 'id');
    }
}
