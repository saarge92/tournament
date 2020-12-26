<?php


namespace App\Repositories\Interfaces;


use App\Models\TournamentMatch;

/**
 * Интерфейс, определяющий функционал репозитория
 * для сущности матчей комманд (сущность TournamentMatch)
 *
 * @package App\Repositories\Interfaces
 */
interface IMatchRepository
{
    function createMatch(array $data): TournamentMatch;

    function getMatch(int $id): ?TournamentMatch;

    function getMatchByTeamTournamentStage(int $teamHome, int $teamGuest, int $idTournament, int $idStage);

    function getMatchesByTeamIdAndTournament(int $idTeam, int $tournamentId, int $stageId);

    function getMatchesByTournamentAndStage(int $tournamentId, int $stageId);

    function getMatchesByTournamentForStages(int $tournamentId, array $stages);
}
