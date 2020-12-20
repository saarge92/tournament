<?php


namespace App\Repositories\Interfaces;

use App\Models\TournamentResult;

/**
 * Интерфейс, определяющий функционал
 * репозитория по работе с сущностю tournament_results
 *
 * @package App\Repositories\Interfaces
 * @author Serdar Durdyev
 */
interface ITournamentResultRepository
{
    function createTournamentResult(array $data): TournamentResult;

    function findTournament(int $idTeam, int $idTournament): ?TournamentResult;

    function getTournamentResultById(int $id): ?TournamentResult;

    function getTournamentResultByTournamentId(int $tournamentId);

    function getTournamentResultByTournamentIdGroupedByDivision(int $tournamentId);
}
