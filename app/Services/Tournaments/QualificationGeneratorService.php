<?php


namespace App\Services\Tournaments;

use App\Interfaces\Matches\IMatchService;
use App\Interfaces\Tournaments\IQualificationGeneratorService;
use App\Interfaces\Tournaments\IQualificationTournamentService;
use App\Interfaces\Tournaments\ITournamentResultService;
use App\Models\Division;
use App\Models\TournamentMatch;
use App\Models\Team;
use App\Models\Tournament;
use App\Repositories\Implementations\TournamentRepository;
use App\Repositories\Interfaces\ITournamentRepository;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;


/**
 * Класс-генератор квалификационных матчей
 *
 * @package App\Services\Tournaments
 * @author Serdar Durdyev
 */
class QualificationGeneratorService implements IQualificationGeneratorService
{
    public IMatchService $matchService;
    public ITournamentRepository $tournamentRepository;
    private object $faker;
    protected ITournamentResultService $tournamentResultService;
    private IQualificationTournamentService $qualificationTournamentService;

    public function __construct(IMatchService $matchService, TournamentRepository $tournamentRepository,
                                ITournamentResultService $tournamentResultService, IQualificationTournamentService $qualificationTournamentService)
    {
        $this->matchService = $matchService;
        $this->tournamentRepository = $tournamentRepository;
        $this->tournamentResultService = $tournamentResultService;
        $this->qualificationTournamentService = $qualificationTournamentService;
        $this->faker = Factory::create('ru_RU');
    }

    /**
     * Генерация квалификационных матчей для каждого дивизиона
     * @return array Вернем квалификационную таблицу турниров
     */
    public function generateQualificationGames(): array
    {
        $tournament = $this->tournamentRepository->getRandomTournament();
        if (!$tournament)
            throw new ConflictHttpException("Турниры отсутствуют в базе");

        $tournamentResults = $this->qualificationTournamentService->getQualificationTournamentResult($tournament->id);
        if (!empty($tournamentResults))
            return $tournamentResults;

        DB::beginTransaction();
        try {
            $response = [];
            $response['tournament_id'] = $tournament->id;
            $response['tournament_name'] = $tournament->name;

            // Перебираем дивизионы и для каждой команды дивизиона проводим по 1 матчу
            // и записываем результаты матчей
            $this->generateMatchesResponseForAllDivisions($tournament->id, $response);

        } catch (\Exception $ex) {
            DB::rollBack();
            throw new ConflictHttpException($ex);
        }
        DB::commit();
        return $response;
    }

    /**
     * Генерация матчей для всех дивизионов команды для турнира
     * Функция генерирует ответ клиенту результаты матчей по-дивизионно
     * @param int $idTournament Id турнира, где проходил турнир
     * @param array $response Ссылка на массив
     */
    private function generateMatchesResponseForAllDivisions(int $idTournament, array &$response)
    {
        $divisions = Division::all();
        foreach ($divisions as $division) {
            $tableRow['division_id'] = $division->id;
            $tableRow['division_name'] = $division->name;
            $teams = $division->teams;
            foreach ($teams as $teamHome) {
                $teamRow[$teamHome->name] = [];
                foreach ($teams as $teamGuest) {
                    if ($teamHome->id != $teamGuest->id) {
                        $matchHappened = $this->matchService->getMatchInfoOnTournamentStage($teamHome->id,
                            $teamGuest->id, $idTournament, 1);
                        $matchHappenedGuestSide = $this->matchService->getMatchInfoOnTournamentStage($teamGuest->id,
                            $teamHome->id, $idTournament, 1);
                        if (!$matchHappened && !$matchHappenedGuestSide) {
                            $match = $this->matchService->addMatchInfo([
                                'id_division' => $division->id,
                                'id_team_home' => $teamHome->id,
                                'id_team_guest' => $teamGuest->id,
                                'id_tournament' => $idTournament,
                                'id_stage' => 1,
                                'count_goal_team_home' => rand(1, 10),
                                'count_goal_team_guest' => rand(1, 10)
                            ]);

                            $this->updateTeamResult($match, $teamGuest, $teamHome, $idTournament);

                            $teamRow[$teamHome->name][$teamGuest->name] = $match->count_goal_team_home . ":" .
                                $match->count_goal_team_guest;
                        } else {
                            if ($matchHappened)
                                $teamRow[$teamHome->name][$teamGuest->name] = $matchHappened->count_goal_team_home
                                    . ":" . $matchHappened->count_goal_team_guest;
                            else if ($matchHappenedGuestSide)
                                $teamRow[$teamHome->name][$teamGuest->name] = $matchHappenedGuestSide->count_goal_team_guest
                                    . ":" . $matchHappenedGuestSide->count_goal_team_home;
                        }
                    }
                }
                $teamResult = $this->tournamentResultService->getTeamResultByTeamAndTournament(
                    $teamHome->id, $idTournament
                );
                if ($teamResult)
                    $teamRow['score'] = $teamResult->points;
                else {
                    $teamRow['score'] = 0;
                    $this->tournamentResultService->createTeamResult($teamHome->id, $idTournament, 0);
                }
                $tableRow['results'][] = $teamRow;
                $teamRow = [];
            }
            $response['tables'][] = $tableRow;
            $tableRow = [];
        }
    }

    /**
     * Инициируем данные результатов матчей на турнире во время генерации турнирной таблицы
     * @param TournamentMatch $match Матч, который проходил
     * @param Team $teamGuest Команда гостей
     * @param Team $teamHome Команда хозяев
     * @param int $tournamentId Id турнира, для которого обновляем результат
     */
    private function updateTeamResult(TournamentMatch $match, Team $teamGuest, Team $teamHome, int $tournamentId)
    {
        if ($match->count_goal_team_home == $match->count_goal_team_guest) {
            $this->tournamentResultService->updateTeamResult($teamGuest->id, $tournamentId, 1);
            $this->tournamentResultService->updateTeamResult($teamHome->id, $tournamentId, 1);
        } else if ($match->count_goal_team_home > $match->count_goal_team_guest) {
            $this->tournamentResultService->updateTeamResult($teamHome->id, $tournamentId, 3);
        } else if ($match->count_goal_team_home < $match->count_goal_team_guest) {
            $this->tournamentResultService->updateTeamResult($teamGuest->id, $tournamentId, 3);
        }
    }
}
