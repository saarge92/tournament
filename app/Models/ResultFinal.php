<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResultFinal extends Model
{
    use SoftDeletes;

    protected $fillable = ['id_team', 'id_tournament', 'place'];
}
