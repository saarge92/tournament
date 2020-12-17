<?php


namespace App\Repositories\Interfaces;

use App\Models\Tournament;

/**
 * Интерфейс, определяющий репозиторий по работе с турнирами
 *
 * @package App\Repositories\Interfaces
 * @author Serdar Durdyev
 */
interface ITournamentRepository
{
    function getTournamentById(int $id): ?Tournament;
}
