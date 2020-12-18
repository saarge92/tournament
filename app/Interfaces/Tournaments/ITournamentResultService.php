<?php


namespace App\Interfaces\Tournaments;


use App\Models\TournamentResult;

/**
 * Интерфейс, определяющий бизнес-логику по работе с результатами турниров tournament_results
 *
 * @package App\Interfaces\Tournaments
 * @author Serdar Durdyev
 */
interface ITournamentResultService
{
    function createTeamResult(int $idTeam, int $idTournament, int $points): TournamentResult;

    function updateTeamResult(int $idTeam, int $idTournament, int $newPoint): TournamentResult;

    function getTeamResultByTeamAndTournament(int $idTeam, int $idTournament): ?TournamentResult;
}
