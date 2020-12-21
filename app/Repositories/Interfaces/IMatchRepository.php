<?php


namespace App\Repositories\Interfaces;


use App\Models\Match;

/**
 * Интерфейс, определяющий функционал репозитория
 * для сущности матчей комманд (сущность Match)
 *
 * @package App\Repositories\Interfaces
 */
interface IMatchRepository
{
    function createMatch(array $data): Match;

    function getMatch(int $id): ?Match;

    function getMatchByTeamTournamentStage(int $teamHome, int $teamGuest, int $idTournament, int $idStage);

    function getMatchesByTeamIdAndTournament(int $idTeam, int $tournamentId, int $stageId);

    function getMatchesByTournamentAndStage(int $tournamentId, int $stageId);

    function getMatchesByTournamentAndStageWithFullReview(int $tournamentId, int $stageId);
}
