<?php


namespace App\Services\Playoffs;


use App\Interfaces\Playoffs\IPlayOffService;
use App\Models\Tournament;
use App\Repositories\Interfaces\IMatchRepository;
use App\Repositories\Interfaces\IResultFinaleRepository;
use App\Repositories\Interfaces\ITournamentRepository;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

/**
 * Сервис по работе с данными плей-офф
 * @package App\Services\Playoffs
 */
class PlayOffService implements IPlayOffService
{
    protected ITournamentRepository $tournamentRepository;
    protected IMatchRepository $matchRepository;
    protected IResultFinaleRepository $finaleRepository;

    public function __construct(ITournamentRepository $tournamentRepository, IMatchRepository $matchRepository,
                                IResultFinaleRepository $resultFinaleRepository)
    {
        $this->tournamentRepository = $tournamentRepository;
        $this->matchRepository = $matchRepository;
        $this->finaleRepository = $resultFinaleRepository;
    }

    /**
     * Получение результатов плей-офф матчей для турнира
     * @param int $tournamentId id турнира
     * @return array Результат плей-офф для каждой стадии
     */
    public function getPlayOffResultsByTournamentId(int $tournamentId)
    {
        $tournament = $this->tournamentRepository->getTournamentById($tournamentId);
        if (!$tournament)
            throw new ConflictHttpException("Такой турнир не найден");
        return $this->generateFullReviewForTournamentPlayOff($tournament);
    }

    /**
     * Генерация подробного отчета плей-офф игр для турнира
     * @param Tournament $tournament Турнир, для которого генерируем ответ
     * @return array Вернем массив с данными финальных стадий (3 место и финал)
     */
    private function generateFullReviewForTournamentPlayOff(Tournament $tournament)
    {
        $response['id_tournament'] = $tournament->name;
        $response['tournament_name'] = $tournament->name;

        $idStageQuarterFinal = 2;
        $idStageSemifinal = 3;
        $idStageThirdPlace = 4;
        $idStageFinale = 5;

        $allFinalMatches = $this->matchRepository->getMatchesByTournamentForStages($tournament->id, [2, 3, 4, 5]);
        $groupedByStageFinalMatches = $allFinalMatches->sortBy('id_stage')->groupBy('id_stage');
        foreach ($groupedByStageFinalMatches as $stageIndex => $finalResults) {
            switch ($stageIndex) {
                case $idStageQuarterFinal:
                {
                    $this->initQuarterResponseForTournamentResponse($finalResults, $response);
                    break;
                }
                case $idStageSemifinal:
                {
                    $this->initSemifinal($finalResults, $response);
                    break;
                }
                case $idStageThirdPlace :
                {
                    $this->initThirdPlaceAndFinalMatches($finalResults, $response, 'third_place_tournament');
                    break;
                }
                case $idStageFinale:
                {
                    $this->initThirdPlaceAndFinalMatches($finalResults, $response, 'final_tournament');
                    break;
                }
            }
        }
        $response['final_results'] = $this->finaleRepository->getFinaleResultByTournamentId($tournament->id);
        return $response;
    }

    /**
     * Формирование ответа с матчами полуфиналов
     * @param iterable $finalResults Финальные результаты матчей
     * @param array $response Ссылка на формируемый масив ответа, возвращаемый клиенту
     */
    private function initSemifinal(iterable $finalResults, array &$response)
    {
        $response['semifinal'] = [];
        $resultMatchesRow = [];
        foreach ($finalResults as $finalResult) {
            $matchResultRow = [
                'team_home' => [
                    'id' => $finalResult->id_team_home,
                    'name' => $finalResult->team_home_name,
                    'id_division' => $finalResult->team_home_division
                ],
                'team_guest' => [
                    'id' => $finalResult->id_team_guest,
                    'name' => $finalResult->team_guest_name,
                    'id_division' => $finalResult->team_guest_division
                ],
                'score' => $finalResult->count_goal_team_home . ":" . $finalResult->count_goal_team_guest
            ];
            $resultMatchesRow['result_matches'][] = $matchResultRow;
            if ($finalResult->count_goal_team_home > $finalResult->count_goal_team_guest) {
                $resultMatchesRow['team_winners'][] = [
                    'id' => $finalResult->id_team_home,
                    'name' => $finalResult->team_home_name,
                    'id_division' => $finalResult->team_home_division
                ];
                $resultMatchesRow['third_place_teams'][] = [
                    'id' => $finalResult->id_team_guest,
                    'name' => $finalResult->team_guest_name,
                    'id_division' => $finalResult->team_guest_division
                ];
            } else if ($finalResult->count_goal_team_home < $finalResult->count_goal_team_guest) {
                $resultMatchesRow['team_winners'][] = [
                    'id' => $finalResult->id_team_guest,
                    'name' => $finalResult->team_guest_name,
                    'id_division' => $finalResult->team_guest_division
                ];
                $resultMatchesRow['third_place_teams'][] = [
                    'id' => $finalResult->id_team_home,
                    'name' => $finalResult->team_home_name,
                    'id_division' => $finalResult->team_home_division
                ];
            }
        }
        $response['semifinal'] = $resultMatchesRow;
    }

    /**
     * Формирование ответа на финальные результаты (матчи за 3 место и финальные матчи)
     * @param iterable $finalResults Финальные результаты матчей
     * @param array $response Ссылка на генерируемый ответ пользователю
     * @param string $keyNameTournament Название турнира в ответе response
     */
    private function initThirdPlaceAndFinalMatches(iterable $finalResults, array &$response, string $keyNameTournament)
    {
        foreach ($finalResults as $finalResult) {
            if ($finalResult->count_goal_team_home > $finalResult->count_goal_team_guest) {
                $response[$keyNameTournament] = [
                    'winner' => [
                        'id' => $finalResult->id_team_home,
                        'name' => $finalResult->team_home_name,
                        'id_division' => $finalResult->team_home_division
                    ],
                    'looser' => [
                        'id' => $finalResult->id_team_guest,
                        'name' => $finalResult->team_guest_name,
                        'id_division' => $finalResult->team_guest_division
                    ],
                    'score' => $finalResult->count_goal_team_home . ":" . $finalResult->count_goal_team_guest
                ];
            } else if ($finalResult->count_goal_team_home < $finalResult->count_goal_team_guest) {
                $response[$keyNameTournament] = [
                    'winner' => [
                        'id' => $finalResult->id_team_guest,
                        'name' => $finalResult->team_guest_name,
                        'id_division' => $finalResult->team_guest_division
                    ],
                    'looser' => [
                        'id' => $finalResult->id_team_home,
                        'name' => $finalResult->team_home_name,
                        'id_division' => $finalResult->team_home_division
                    ],
                    'score' => $finalResult->count_goal_team_guest . ":" . $finalResult->count_goal_team_home
                ];
            }
        }
    }

    /**
     * Инициализируем ответ для четверть-финальных матчей при получении данных плей-офф турнира
     * @param iterable $finalResults Финальные результаты матчей
     * @param array $response Ссылка на результирующий массив, который пойдет в ответ пользователю
     */
    private function initQuarterResponseForTournamentResponse(iterable $finalResults, array &$response)
    {
        $response['quarter_final'] = [];
        $resultMatchesRow = [];
        foreach ($finalResults as $finalResult) {
            $matchResultRow = [
                'team_home' => [
                    'id' => $finalResult->id_team_home,
                    'name' => $finalResult->team_home_name,
                    'id_division' => $finalResult->team_home_division
                ],
                'team_guest' => [
                    'id' => $finalResult->id_team_guest,
                    'name' => $finalResult->team_guest_name,
                    'id_division' => $finalResult->team_guest_division
                ],
                'score' => $finalResult->count_goal_team_home . ":" . $finalResult->count_goal_team_guest
            ];
            $resultMatchesRow['result_matches'][] = $matchResultRow;
            if ($finalResult->count_goal_team_home > $finalResult->count_goal_team_guest) {
                $resultMatchesRow['team_winners'][] = [
                    'id' => $finalResult->id_team_home,
                    'name' => $finalResult->team_home_name,
                    'id_division' => $finalResult->team_home_division
                ];
            } else if ($finalResult->count_goal_team_home < $finalResult->count_goal_team_guest) {
                $resultMatchesRow['team_winners'][] = [
                    'id' => $finalResult->id_team_guest,
                    'name' => $finalResult->team_guest_name,
                    'id_division' => $finalResult->team_guest_division
                ];
            }
        }
        $response['quarter_final'] = $resultMatchesRow;
    }
}
