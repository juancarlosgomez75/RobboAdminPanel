<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyMaintenance extends Model
{
    protected $table = 'study_maintenance'; // Especifica el nombre de la tabla

    public function author_info()
    {
        return $this->belongsTo(User::class, 'author', 'id'); // 'rank' es la clave foránea en users, 'id' es la clave primaria en ranks
    }
}
