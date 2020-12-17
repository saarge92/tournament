<?php


namespace App\Repositories\Implementations;


use App\Models\Tournament;
use App\Repositories\Interfaces\ITournamentRepository;
use Illuminate\Support\Facades\DB;

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

    /**
     * Создаем данные для турнира
     * @param array $data Данные для создания
     * @return mixed
     */
    function createTournament(array $data): Tournament
    {
        return Tournament::create($data);
    }

    /**
     * Получим любой турниры, который ранее не проводился
     */
    function getUnsettledTournaments()
    {
        return Tournament::whereNotExists(function ($query) {
            $query->select(
                DB::raw(1))->from('tournament_results')
                ->whereRaw('tournaments.id = tournament_results.id_tournament');
        })->get();
    }
}
