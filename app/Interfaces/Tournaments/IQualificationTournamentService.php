<?php


namespace App\Interfaces\Tournaments;

/**
 * Интерфейс по работе с результатами квалификационных данных
 *
 * @package App\Interfaces\Tournaments
 * @author Serdar Durdyev
 */
interface IQualificationTournamentService
{
    function getQualificationTournamentResult(int $tournamentId);
}
