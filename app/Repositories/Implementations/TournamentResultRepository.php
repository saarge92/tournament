<?php


namespace App\Repositories\Implementations;


use App\Models\Division;
use App\Models\TournamentResult;
use App\Repositories\Interfaces\ITournamentResultRepository;
use Cassandra\Collection;
use Illuminate\Support\Facades\DB;

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

    /**
     * Получаем результаты турниров по id турнира
     * @param int $id Id турнира
     * @return TournamentResult|null Вернем турнир или пустую запись
     */
    public function getTournamentResultById(int $id): ?TournamentResult
    {
        return TournamentResult::find($id);
    }

    /**
     * Получение данных результатов турнира по id турнира
     * @param int $tournamentId Id турнира
     * @return mixed
     */
    function getTournamentResultByTournamentId(int $tournamentId)
    {
        return TournamentResult::where(['id_tournament' => $tournamentId])
            ->orderBy('points', 'DESC')->get();
    }

    /**
     * Получение данных результатов турнира по Id
     * по каждому дивизиону
     * @param int $tournamentId Id турнира
     * @return \Illuminate\Support\Collection
     */
    function getTournamentResultByTournamentIdGroupedByDivision(int $tournamentId): Collection
    {
        return DB::table('divisions as d')
            ->join('teams as t', DB::raw('t.id_division'), '=', DB::raw('d.id'))
            ->join('tournament_results  as tr', DB::raw('tr.id_team'), '=', DB::raw('t.id'))
            ->where(['tr.id_tournament' => $tournamentId])
            ->orderBy('d.id')->orderBy('t.id')
            ->get();
    }
}
