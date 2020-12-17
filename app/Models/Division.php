<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    public function teams()
    {
        return $this->hasMany(Team::class, 'id_division', 'id');
    }
}
