<?php


namespace App\Interfaces\Matches;

use App\Models\TournamentMatch;

/**
 * Интерфейс по работе с бизнес-логикой
 * по управлению матчами в турнирах
 *
 * @package App\Interfaces\Matches
 * @author Serdar Durdyev
 */
interface IMatchService
{
    function addMatchInfo(array $data): TournamentMatch;

    function getMatchInfoOnTournamentStage(int $idHomeTeam, int $idTeamGuest, int $tournamentId, int $idStage);

    function getMatchesForTeam(int $teamId, int $tournament, int $idStage);
}
