<?php


namespace App\Repositories\Implementations;


use App\Models\Match;
use App\Repositories\Interfaces\IMatchRepository;

/**
 * Репозиторий по работе с сущностью матчей (matches)
 * @package App\Repositories\Implementations
 */
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

    function getMatchByTeamTournamentStage(int $teamHome, int $teamGuest, int $idTournament, int $idStage): ?Match
    {
        return Match::where([
            'id_team_home' => $teamHome,
            'id_team_guest' => $teamGuest,
            'id_tournament' => $idTournament,
            'id_stage' => $idStage,
        ])->first();
    }
}
