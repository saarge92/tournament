<?php


namespace App\Repositories\Implementations;


use App\Models\Match;
use App\Repositories\Interfaces\IMatchRepository;

class MatchRepository implements IMatchRepository
{

    function createMatch(array $data): Match
    {
        return Match::create($data);
    }

    function getMatch(int $id): ?Match
    {
        return Match::find($id);
    }
}
