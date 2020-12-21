<?php


namespace App\Interfaces\Playoffs;

/**
 * Интерфейс для бизнес-логики по работе с матчами плей-офф
 * @package App\Interfaces\Playoffs
 */
interface IPlayOffService
{
    function getPlayOffResultsByTournamentId(int $tournamentId);
}
