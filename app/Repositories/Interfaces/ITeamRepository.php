<?php


namespace App\Repositories\Interfaces;

use App\Models\Team;

/**
 * Интерфейс для репозитория по работе с сущностями команд(teams)
 *
 * @package App\Repositories\Interfaces
 */
interface ITeamRepository
{
    function getTeam(int $id): ?Team;
}
