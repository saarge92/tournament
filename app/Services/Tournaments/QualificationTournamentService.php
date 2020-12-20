<?php


namespace App\Services\Tournaments;


use App\Interfaces\Tournaments\IQualificationTournamentService;
use App\Models\Division;
use App\Models\Team;
use App\Models\Tournament;
use App\Repositories\Interfaces\IMatchRepository;
use App\Repositories\Interfaces\ITournamentRepository;
use App\Repositories\Interfaces\ITournamentResultRepository;

/**
 * Сервис по работе с результатами турниров
 * Содержит методы, который предоставляют данные турнира в удобно-читаемом виде
 * (как например, результаты турнира, сгруппированного по дивизионам и турнирам)
 *
 * @package App\Services\Tournaments
 */
class QualificationTournamentService implements IQualificationTournamentService
{
    public ITournamentResultRepository $tournamentResultRepository;
    public ITournamentRepository $tournamentRepository;
    public IMatchRepository $matchRepository;

    public function __construct(ITournamentResultRepository $tournamentResultRepository, ITournamentRepository $tournamentRepository,
                                IMatchRepository $matchRepository)
    {
        $this->tournamentResultRepository = $tournamentResultRepository;
        $this->tournamentRepository = $tournamentRepository;
        $this->matchRepository = $matchRepository;
    }

    /**
     * Получение данных квалификации, сгруппированных по дивизионно
     * @param int $tournamentId Id Турнира
     * @return array Вернем массив с данными турнира
     */
    public function getQualificationTournamentResult(int $tournamentId)
    {
        $tournament = $this->tournamentRepository->getTournamentById($tournamentId);
        if (!$tournament)
            return [];

        $tournamentResults = $this->tournamentResultRepository->getTournamentResultByTournamentIdGroupedByDivision($tournamentId)
            ->groupBy('id_division');
        return $this->generateResponseForTournament($tournamentResults, $tournament);
    }

    /**
     * Формируем ответ для турнира для каждого дивизиона с результатами матча
     * @param iterable $tournamentResults Результаты турнира, сгруппированные по дивизионам
     * @param Tournament $tournament Турнир
     * @return mixed
     */
    private function generateResponseForTournament(iterable $tournamentResults, Tournament $tournament)
    {
        $response['tournament_id'] = $tournament->id;
        $response['tournament_name'] = $tournament->name;

        // Перебор результатов турнира по дивизионам, где $divisionIndex - это ключ-Id дивизиона
        foreach ($tournamentResults as $divisionIndex => $divisionResults) {
            $tableRow['division_id'] = $divisionIndex;
            $division = Division::find($divisionIndex);
            $division ? $tableRow['division_name'] = $division->name : $tableRow['division_name'] = null;

            $this->formatResponseForEveryDivisions($divisionResults, $tournament, $tableRow);
            $response['tables'][] = $tableRow;
            $tableRow = [];
        }
        return $response;
    }

    /**
     * Генерируем ответ для каждого дивизиона результаты турнирной таблицы
     * @param iterable $divisionResults Результаты дивизиона
     * @param Tournament $tournament Текущий турнир, где проходит турнир
     * @param array $tableRow Текущая строка для ответа в список results ответа
     */
    private function formatResponseForEveryDivisions(iterable $divisionResults, Tournament $tournament, array &$tableRow)
    {
        foreach ($divisionResults as $divisionResult) {
            $matchRow = [];
            $matchResults = $this->matchRepository->getMatchesByTeamIdAndTournament($divisionResult->id_team, $tournament->id, 1);
            foreach ($matchResults as $matchResult) {
                if ($matchResult->id_team_home == $divisionResult->id_team) {
                    $teamGuest = $divisionResults->where('id', $matchResult->id_team_guest)->first();

                    $matchRow[$divisionResult->name][$teamGuest->name] = $matchResult->count_goal_team_home . ":"
                        . $matchResult->count_goal_team_guest;

                } else if ($matchResult->id_team_guest == $divisionResult->id_team) {
                    $teamGuest = $divisionResults->where('id', $matchResult->id_team_home)->first();

                    $matchRow[$divisionResult->name][$teamGuest->name] = $matchResult->count_goal_team_guest . ":"
                        . $matchResult->count_goal_team_home;
                }
                $matchRow['score'] = $divisionResult->points;
            }
            $tableRow['results'][] = $matchRow;
        }
    }
}
