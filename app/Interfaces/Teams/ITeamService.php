<?php


namespace App\Interfaces\Teams;

/**
 * Интерфейс по работе с командами
 * @package App\Interfaces\TeamService
 * @author Serdar Durdyev
 */
interface ITeamService
{
    function getTeamByDivisionId(int $divisionId);
}
