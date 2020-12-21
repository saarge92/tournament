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

            $semifinalResults = $this->generateSemifinal($quarterFinalResult['teams'], $idTournament);
            $finalResponse['semifinal'] = $semifinalResults;

            $thirdPlaceResult = $this->generateThirdPlaceAndFinal($semifinalResults['third_place_teams'], $idTournament, 4, 3, 4);
            $finalResponse['third_place_tournament'] = $thirdPlaceResult;


            $finalResults = $this->generateThirdPlaceAndFinal($semifinalResults['teams'], $idTournament, 5, 1, 2);
            $finalResponse['final_tournament'] = $finalResults;

            $this->fillFinaleResults($finalResponse, $finalResults, 1, 2);
            $this->fillFinaleResults($finalResponse, $thirdPlaceResult, 3, 4);

        } catch (\Exception $exception) {
            DB::rollBack();
            throw new ConflictHttpException($exception);
        }
        return $finalResponse;
    }

    /**
     * Заполняем результаты команд в финале и в борьбе за 3 место
     * @param iterable $finalResponse По ссылке передаем текущий массив с финальными результатами
     * @param iterable $playoffResults Результат матчей за 3 место или финал
     * @param int $placeWinner Место которое занимает команда при выигрыше
     * @param int $placeLooser Место, которое занимает команда при проигрыше
     */
    private function fillFinaleResults(iterable &$finalResponse, iterable $playoffResults, int $placeWinner, int $placeLooser)
    {
        $finalResponse['final_results'][] = [
            'id_team' => $playoffResults['winner']['id'],
            'name' => $playoffResults['winner']['name'],
            'id_division' => $playoffResults['winner']['id_division'],
            'place' => $placeWinner,
        ];
        $finalResponse['final_results'][] = [
            'id_team' => $playoffResults['looser']['id'],
            'name' => $playoffResults['looser']['name'],
            'id_division' => $playoffResults['looser']['id_division'],
            'place' => $placeLooser
        ];
    }

    /**
     * Генерация матча за 3 место
     * @param array $teams Команды, участвующие в матче
     * @param int $idTournament Id турнира
     * @param int $idStage Id этапа на котором проходят финальные игры (игра за третье и финал)
     * @param int $placeWinner Место для победителя
     * @param int $placeLooser Место для проигравшего
     * @return array Вернем массив с результатами матчей (3-место и финал)
     */
    private function generateThirdPlaceAndFinal(array $teams, int $idTournament, int $idStage,
                                                int $placeWinner, int $placeLooser): array
    {
        $response = [];
        $countGoalHome = rand(1, 10);
        $countGoalGuest = rand(1, 10);
        $teamHome = $teams[0];
        $teamGuest = $teams[1];
        if ($countGoalGuest == $countGoalHome)
            $countGoalHome += 1;
        $this->matchRepository->createMatch([
            'id_tournament' => $idTournament,
            'id_team_home' => $teamHome['id'],
            'id_team_guest' => $teamGuest['id'],
            'id_stage' => $idStage,
            'count_goal_team_home' => $countGoalHome,
            'count_goal_team_guest' => $countGoalGuest
        ]);
        if ($countGoalHome > $countGoalGuest) {
            $response['winner'] = $teamHome;
            $response['looser'] = $teamGuest;
            $response['score'] = $countGoalHome . ":" . $countGoalGuest;
            $this->finaleRepository->createFinalResult([
                'id_tournament' => $idTournament,
                'id_team' => $teamHome['id'],
                'place' => $placeWinner
            ]);
            $this->finaleRepository->createFinalResult([
                'id_tournament' => $idTournament,
                'id_team' => $teamGuest['id'],
                'place' => $placeLooser
            ]);
        } else {
            $response['winner'] = $teamGuest;
            $response['looser'] = $teamHome;
            $response['score'] = $countGoalGuest . ":" . $countGoalHome;
            $this->finaleRepository->createFinalResult([
                'id_tournament' => $idTournament,
                'id_team' => $teamGuest['id'],
                'place' => $placeWinner
            ]);
            $this->finaleRepository->createFinalResult([
                'id_tournament' => $idTournament,
                'id_team' => $teamHome['id'],
                'place' => $placeLooser
            ]);
        }
        return $response;
    }


    /**
     * Генерация полуфиналов
     * @param array $teams Команды, участвующие в полуфинальных матчах
     * @param int $idTournament Id турнира, в котором проходят полуфинальные матчи
     * @return array Вернем массив с данными победителей
     */
    private function generateSemifinal(array $teams, int $idTournament): array
    {
        if (count($teams) != 4)
            throw new ConflictHttpException("Невозможно провести полуфинал, если команд больше или меньше 4");

        $gamePlan = [0 => 3, 1 => 2];
        $response = [];
        foreach ($gamePlan as $firstIdTeam => $secondIdTeam) {
            $teamHome = $teams[$firstIdTeam];
            $teamGuest = $teams[$secondIdTeam];

            $countGoalTeamHome = rand(1, 10);
            $countGoalTeamGuest = rand(1, 10);
            if ($countGoalTeamHome == $countGoalTeamGuest)
                $countGoalTeamHome += 1;

            $this->matchRepository->createMatch([
                'id_tournament' => $idTournament,
                'id_stage' => 3,
                'id_team_home' => $teamHome['id'],
                'id_team_guest' => $teamGuest['id'],
                'count_goal_team_home' => $countGoalTeamHome,
                'count_goal_team_guest' => $countGoalTeamGuest
            ]);
            if ($countGoalTeamHome > $countGoalTeamGuest) {
                $response['teams'][] = $teamHome;
                $response['third_place_teams'][] = $teamGuest;
            } else if ($countGoalTeamHome < $countGoalTeamGuest) {
                $response['teams'][] = $teamGuest;
                $response['third_place_teams'][] = $teamHome;
            }
            $response['result_matches'][] = [
                'team_home' => $teamHome,
                'team_guest' => $teamGuest,
                'score' => $countGoalTeamHome . ":" . $countGoalTeamGuest
            ];
        }
        return $response;
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
