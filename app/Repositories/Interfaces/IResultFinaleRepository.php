<?php


namespace App\Repositories\Interfaces;

use App\Models\ResultFinal;

/**
 * Интерфейс для репозитория по работе с сущностями result_finals
 *
 * @package App\Repositories\Interfaces
 * @author Serdar Durdyev
 */
interface IResultFinaleRepository
{
    function createFinalResult(array $data): ResultFinal;

    function getFinalResultByTeamAndTournament(int $idTeam, int $idTournament): ?ResultFinal;
}
