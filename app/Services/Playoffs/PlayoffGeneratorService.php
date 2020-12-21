<?php


namespace App\Services\Playoffs;


use App\Interfaces\Playoffs\IPlayoffGeneratorService;
use App\Repositories\Interfaces\IMatchRepository;
use App\Repositories\Interfaces\IResultFinaleRepository;
use App\Repositories\Interfaces\ITournamentResultRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

/**
 * Генератор плей-оф матчей для турниров
 *
 * @package App\Services\Playoffs
 * @author Serdar Durdyev
 */
class PlayoffGeneratorService implements IPlayoffGeneratorService
{
    public ITournamentResultRepository $tournamentResultRepository;
    public IResultFinaleRepository $finaleRepository;
    public IMatchRepository $matchRepository;

    public function __construct(ITournamentResultRepository $tournamentResultRepository, IResultFinaleRepository $resultFinaleRepository,
                                IMatchRepository $matchRepository)
    {
        $this->tournamentResultRepository = $tournamentResultRepository;
        $this->finaleRepository = $resultFinaleRepository;
        $this->matchRepository = $matchRepository;
    }

    /**
     * Генерирование плей-оф матчей для турнира
     * @param int $idTournament Id турнира
     * @return array Вернем массив данных с генерированными плей-офф
     */
    public function generatePlayOfForTournament(int $idTournament)
    {
        $tournamentResults = $this->tournamentResultRepository->getTournamentResultByTournamentIdGroupedByDivision($idTournament);
        if (!$tournamentResults)
            return [];

        $resultFinale = $this->finaleRepository->getFinaleResultByTournamentId($idTournament);
        if (count($resultFinale) > 0)
            return $resultFinale;

        $groupedByDivisionTopTeamResult = $this->generateTopTeamResultByDivision($tournamentResults);
        $finalResponse = [];
        DB::beginTransaction();
        try {
            $quarterFinalResult = $this->generateQuarterFinale($groupedByDivisionTopTeamResult);
            $finalResponse['quarter_final'] = $quarterFinalResult;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new ConflictHttpException($exception);
        }
        return $finalResponse;
    }

    /**
     * Генерация четверь-финала
     * @param array $tournamentResults Результаты турнира по дивизионам
     * @return mixed
     */
    private function generateQuarterFinale(array $tournamentResults)
    {
        $gamePlans = [0 => 3, 1 => 2, 2 => 1, 3 => 0];
        $countDivisions = array_keys($tournamentResults);
        if (count($countDivisions) < 2)
            throw new ConflictHttpException("Невозможно провести четвертьфинал между дивизионами. Дивизионов должно быть всего два!");

        $teamsTopForQuarterFinaleFirstDivision = $tournamentResults[$countDivisions[0]];
        $teamsTopForQuarterFinaleSecondDivision = $tournamentResults[$countDivisions[1]];

        $semifinaleTeams = [];
        foreach ($gamePlans as $firstTeamPlace => $secondTeamPlace) {
            $teamHome = $teamsTopForQuarterFinaleFirstDivision[$firstTeamPlace];
            $teamGuest = $teamsTopForQuarterFinaleSecondDivision[$firstTeamPlace];

            $countGoalHome = rand(1, 10);
            $countGoalGuest = rand(1, 10);
            if ($countGoalHome == $countGoalGuest)
                $countGoalHome += $countGoalHome + 1;

            $this->matchRepository->createMatch([
                'id_team_home' => $teamHome->id,
                'id_team_guest' => $teamGuest->id,
                'count_goal_home' => $countGoalHome,
                'count_goal_guest' => $countGoalGuest,
                'id_stage' => 2
            ]);

            $semifinaleTeams['result_matches'][] = [
                'team_home' => $this->getShortInfoAboutTeamInfo($teamHome),
                'team_guest' => $this->getShortInfoAboutTeamInfo($teamGuest),
                'score' => $countGoalHome . ":" . $countGoalGuest
            ];

            if ($countGoalHome > $countGoalGuest)
                $semifinaleTeams['teams'][] = $this->getShortInfoAboutTeamInfo($teamHome);

            else if ($countGoalHome < $countGoalGuest)
                $semifinaleTeams['teams'][] = $this->getShortInfoAboutTeamInfo($teamGuest);

        }
        return $semifinaleTeams;
    }

    /**
     *
     * @param \stdClass $teamInfo
     * @return array
     */
    private function getShortInfoAboutTeamInfo(\stdClass $teamInfo)
    {
        return [
            'id' => $teamInfo->id,
            'name' => $teamInfo->name,
            'id_division' => $teamInfo->id_division
        ];
    }

    /**
     * Генерация для каждого дивизиона топ 4 команды
     * @param Collection $tournamentResults Результаты команд в турнире
     * @return array Вернем массив данных с топ 4 результатов для каждого дивизиона
     */
    private function generateTopTeamResultByDivision(Collection $tournamentResults)
    {
        $groupedByDivisionTournamentResults = $tournamentResults->groupBy('id_division');
        $response = [];
        foreach ($groupedByDivisionTournamentResults as $divisionId => $divisionResults) {
            $orderedByPointsResult = $divisionResults->sortByDesc('points')->take(4);
            $response[$divisionId] = $orderedByPointsResult->values();
        }
        return $response;
    }
}
