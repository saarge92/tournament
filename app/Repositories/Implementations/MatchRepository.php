<?php


namespace App\Repositories\Implementations;


use App\Models\Match;
use App\Repositories\Interfaces\IMatchRepository;

/**
 * Репозиторий по работе с сущностью матчей (matches)
 * @package App\Repositories\Implementations
 */
class MatchRepository implements IMatchRepository
{
    /**
     * Создание матча в базе
     * @param array $data Данные инициализации матча
     * @return Match Вернем созданный матч
     */
    function createMatch(array $data): Match
    {
        return Match::create($data);
    }

    /**
     * Получение матча по id
     * @param int $id Id матча
     * @return Match|null Вернем найденный матч
     */
    function getMatch(int $id): ?Match
    {
        return Match::find($id);
    }

    /**
     * Получение матча по id турнира, id турнирного этапа и id команд
     * @param int $teamHome Id команды хозяев
     * @param int $teamGuest Id команды гостей
     * @param int $idTournament Id турнира
     * @param int $idStage Id этапа турнира
     * @return Match|null Вернем информацию о матче или пустую запись
     */
    function getMatchByTeamTournamentStage(int $teamHome, int $teamGuest, int $idTournament, int $idStage): ?Match
    {
        return Match::where([
            'id_team_home' => $teamHome,
            'id_team_guest' => $teamGuest,
            'id_tournament' => $idTournament,
            'id_stage' => $idStage,
        ])->first();
    }

    /**
     * Получение данных матча одной команды, где она была либо хозяином либо гостем в матче
     * @param int $idTeam Id команды
     * @param int $tournamentId Id турнира
     * @param int $stageId Id группового турнира
     * @return mixed
     */
    function getMatchesByTeamIdAndTournament(int $idTeam, int $tournamentId, int $stageId)
    {
        return Match::whereRaw("(id_team_home = ? OR id_team_guest = ?) AND (id_tournament = ? and id_stage =?)",
            [$idTeam, $idTeam, $tournamentId, $stageId])->orderBy('created_at', 'DESC')->get();
    }
}
