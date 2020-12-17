<?php


namespace App\Repositories\Implementations;


use App\Models\TournamentResult;
use App\Repositories\Interfaces\ITournamentResultRepository;

/**
 * Репозиторий для работы с сущностью tournament_results
 *
 * @package App\Repositories\Implementations
 * @author Serdar Durdyev
 */
class TournamentResultRepository implements ITournamentResultRepository
{
    /**
     * Создание данных результатов турнира для команды
     * @param array $data Данные для создания
     * @return TournamentResult Вернем созданную запись
     */
    function createTournamentResult(array $data): TournamentResult
    {
        return TournamentResult::create($data);
    }

    /**
     * Поиск данных результатов турнира для команды
     * @param int $idTeam Id команды, для которой происходит поиск
     * @param int $idTournament Id турнира  по которому ищем результатом
     * @return TournamentResult|null Вернем найденную запись или пустую запись
     */
    function findTournament(int $idTeam, int $idTournament): ?TournamentResult
    {
        return TournamentResult::where(['id_team' => $idTeam, 'id_tournament' => $idTournament])->first();
    }
}
