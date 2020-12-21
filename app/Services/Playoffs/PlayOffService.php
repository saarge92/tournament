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
        $isStageFinale = 5;

        $allFinalMatches = $this->matchRepository->getMatchesByTournamentForStages($tournament->id, [2, 3, 4, 5]);
        $groupedByStageFinalMatches = $allFinalMatches->groupBy('id_stage');
        foreach ($groupedByStageFinalMatches as $stageIndex => $finalResults) {
            switch ($stageIndex) {
                case $idStageQuarterFinal:
                {
                    $this->initQuarterResponseForTournamentResponse($finalResults, $response);
                    break;
                }
            }
        }
        return $response;
    }

    private function initQuarterResponseForTournamentResponse(iterable $finalResults, &$response)
    {
        $response['quarter_finale'] = [];
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
            if ($finalResult->count_goal_team_home > $finalResult->count_goal_team_guest) {

            } else if ($finalResult->count_goal_team_home < $finalResult->count_goal_team_guest) {

            }
            $resultMatchesRow['result_matches'][] = $matchResultRow;
        }
        $response['quarter_final'][] = $resultMatchesRow;
    }
}
