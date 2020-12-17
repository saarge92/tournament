<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'id_division'];

    public function division()
    {
        return $this->belongsTo(Division::class, 'id_division', 'id');
    }
}
