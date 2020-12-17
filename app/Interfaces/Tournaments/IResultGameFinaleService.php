<?php


namespace App\Interfaces\Tournaments;

/**
 * Интерфейс по работе с финальными играми на выбывание
 * (четверть-финал,полу-финал,финал, игра за 3 место)
 *
 * @package App\Interfaces\Tournaments
 * @author Serdar Durdyev
 */
interface IResultGameFinaleService
{
    function createResultFinale(int $idTeam, int $idTournament, int $place);
}
