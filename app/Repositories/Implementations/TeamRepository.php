<?php


namespace App\Repositories\Implementations;


use App\Models\Team;
use App\Repositories\Interfaces\ITeamRepository;

/**
 * Репозиторий по работе с командами
 *
 * @package App\Repositories\Implementations
 * @author Serdar Durdyev
 */
class TeamRepository implements ITeamRepository
{
    /**
     * Получение команды по Id
     * @param int $id Id команды
     * @return Team|null Вернем найденную команду
     */
    function getTeam(int $id): ?Team
    {
        return Team::find($id);
    }
}
