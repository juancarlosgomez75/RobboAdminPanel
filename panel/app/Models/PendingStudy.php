<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingStudy extends Model
{
    protected $table = 'pending_studies';

    public function author_info()
    {
        return $this->belongsTo(User::class, 'author', 'id');
    }

}
