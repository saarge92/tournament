<?php


namespace App\Services\Matches;


use App\Interfaces\Matches\IMatchService;
use App\Models\Division;
use App\Models\TournamentMatch;
use App\Models\Stage;
use App\Models\Team;
use App\Models\Tournament;
use App\Repositories\Interfaces\IMatchRepository;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

/**
 * Класс-сервис, содержащий бизнес-логику
 * по работе с матчами команд
 *
 * @package App\Services\Matches
 * @author Serdar Durdyev
 */
class MatchService implements IMatchService
{
    private IMatchRepository $matchRepository;

    public function __construct(IMatchRepository $matchRepository)
    {
        $this->matchRepository = $matchRepository;
    }

    /**
     * Создаем матч в нашей системе
     *
     * @param array $data Данные для создания матча
     * @return TournamentMatch Вернем созданный матч
     */
    public function addMatchInfo(array $data): TournamentMatch
    {
        $division = Division::find($data['id_division']);
        if (!$division)
            throw new ConflictHttpException("Данный дивизион c id_division отсутсвует в базе");

        $teamHome = Team::find($data['id_team_home']);
        if (!$teamHome)
            throw new ConflictHttpException("Отсутствует такая команда с id_team_home");

        $teamGuest = Team::find($data['id_team_guest']);
        if (!$teamGuest)
            throw new ConflictHttpException("Отсутствует такая команда id_team_guest");

        if ($data['id_team_guest'] == $data['id_team_home'])
            throw new ConflictHttpException("Команда не может играть сама с собой");

        if ($data['count_goal_team_home'] < 0 || $data['count_goal_team_guest'] < 0)
            throw new ConflictHttpException("Счет не может быть отрицательным");

        $stage = Stage::find($data['id_stage']);
        if (!$stage)
            throw new ConflictHttpException("Такой этап соревнования с id_stage не найден");

        $tournament = Tournament::find($data['id_tournament']);
        if (!$tournament)
            throw new ConflictHttpException("Турнир с таким id_tournament не найден");

        return $this->matchRepository->createMatch($data);
    }

    /**
     * Получаем данные матча между командами на определенном этапе квалификации
     * @param int $idHomeTeam Id команды хозяев
     * @param int $idTeamGuest Id Команды гостей
     * @param int $tournamentId Id турнира
     * @param int $idStage Id этапа турнира
     * @return mixed
     */
    public function getMatchInfoOnTournamentStage(int $idHomeTeam, int $idTeamGuest, int $tournamentId, int $idStage)
    {
        return $this->matchRepository->getMatchByTeamTournamentStage($idHomeTeam, $idTeamGuest, $tournamentId, $idStage);
    }

    /**
     * Получение матчей для команды
     * @param int $teamId Id команды
     * @param int $tournament Id турнира
     * @param int $idStage Id этапа, на котором проводят турнир
     * @return mixed
     */
    function getMatchesForTeam(int $teamId, int $tournament, int $idStage)
    {
        return $this->matchRepository->getMatchesByTeamIdAndTournament($teamId, $tournament);
    }
}
