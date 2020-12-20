<?php


namespace App\Repositories\Implementations;


use App\Models\ResultFinal;
use App\Repositories\Interfaces\IResultFinaleRepository;

/**
 * Репозиторий по работе с данными финальных стадий матчей result_finals
 * @package App\Repositories\Implementations
 */
class ResultFinaleRepository implements IResultFinaleRepository
{
    /**
     * Создание данных финала result_finales
     * @param array $data Данны инициализации
     * @return ResultFinal Вернем созданную запись
     */
    function createFinalResult(array $data): ResultFinal
    {
        return ResultFinal::create($data);
    }

    /**
     * Получаем данные финала по id команды и турнира
     * @param int $idTeam Id команды
     * @param int $idTournament Id турнира
     * @return ResultFinal|null
     */
    function getFinalResultByTeamAndTournament(int $idTeam, int $idTournament): ?ResultFinal
    {
        return ResultFinal::where(['id_team' => $idTeam, 'id_tournament' => $idTournament])->first();
    }

    /**
     * Получение результатов турнира по id турнира
     * @param int $idTournament Id турнира
     * @return mixed
     */
    public function getFinaleResultByTournamentId(int $idTournament)
    {
        return ResultFinal::where(['id_tournament' => $idTournament])->get();
    }
}
