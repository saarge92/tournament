<?php


namespace App\Services\Teams;


use App\Interfaces\Teams\ITeamService;
use App\Models\Team;

/**
 * Сервис по работе с командами
 * @package App\Services\Teams
 * @author Serdar Durdyev
 */
class TeamService implements ITeamService
{

    /**
     * Получение команд по id дивизиона, к которому они принадлежат
     * @param int $divisionId Id дивизиона
     * @return mixed
     */
    function getTeamByDivisionId(int $divisionId)
    {
        return Team::where(['id_division' => $divisionId])
            ->orderBy('id')
            ->get();
    }
}
