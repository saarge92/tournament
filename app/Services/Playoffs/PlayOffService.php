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

        $quarterFinalResults = $this->getInfoAboutQuarterFinaleMatches($tournament->id);
        $response['quarter_final'] = $quarterFinalResults;


        $response['final_results'] = $this->finaleRepository->getFinaleResultByTournamentId($tournament->id);
        return $response;
    }

    /**
     * Получение данных четвертьфинала
     * @param int $idTournament Id турнира
     * @return array Вернем массив с данными турниров
     */
    public function getInfoAboutQuarterFinaleMatches(int $idTournament): array
    {
        $quarterFinalResponse = [];
        $quarterFinalResults = $this->matchRepository->getMatchesByTournamentAndStageWithFullReview($idTournament, 2);
        foreach ($quarterFinalResults as $quarterFinalResult) {
            $quarterFinalResponse['result_matches'][] = [
                'team_home' => [
                    'id' => $quarterFinalResult->id_team_home,
                    'name' => $quarterFinalResult->team_home_name,
                    'id_division' => $quarterFinalResult->team_home_division
                ],
                'team_guest' => [
                    'id' => $quarterFinalResult->id_team_guest,
                    'name' => $quarterFinalResult->team_guest_name,
                    'id_division' => $quarterFinalResult->team_guest_division
                ],
                'score' => $quarterFinalResult->count_goal_team_home . ":" . $quarterFinalResult->count_goal_team_guest
            ];
            if ($quarterFinalResult->count_goal_team_home > $quarterFinalResult->count_goal_team_guest)
                $quarterFinalResponse['team_winners'][] = [
                    'id' => $quarterFinalResult->id_team_home,
                    'name' => $quarterFinalResult->team_home_name,
                    'id_division' => $quarterFinalResult->team_home_division
                ];
            else if ($quarterFinalResult->count_goal_team_home < $quarterFinalResult->count_goal_team_guest)
                $quarterFinalResponse['team_winners'][] = [
                    'id' => $quarterFinalResult->id_team_guest,
                    'name' => $quarterFinalResult->team_guest_name,
                    'id_division' => $quarterFinalResult->team_guest_division
                ];
        }
        return $quarterFinalResponse;
    }

}
