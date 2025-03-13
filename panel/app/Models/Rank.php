<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    protected $table = 'ranks'; // Especifica el nombre de la tabla
    public function users()
    {
        return $this->hasMany(User::class, 'rank');
    }
}
