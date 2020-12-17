<?php


namespace App\Repositories\Implementations;


use App\Models\Tournament;
use App\Repositories\Interfaces\ITournamentRepository;

/**
 * Репозиторий по работе с сущности турниры (tournaments)
 *
 * @package App\Repositories\Implementations
 * @author Serdar Durdyev
 */
class TournamentRepository implements ITournamentRepository
{
    /**
     * Получение турнира по id
     * @param int $id Id турнира
     * @return Tournament|null Вернем найденную или пустую запись
     */
    function getTournamentById(int $id): ?Tournament
    {
        return Tournament::find($id);
    }
}
