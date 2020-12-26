<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TournamentMatch extends Model
{
    protected $table = 'matches';

    use SoftDeletes;

    protected $fillable = [
        'id_division',
        'id_team_home',
        'id_team_guest',
        'id_tournament',
        'id_stage',
        'count_goal_team_home',
        'count_goal_team_guest',

    ];
}
