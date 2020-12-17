<?php


namespace App\Services\Tournaments;

use App\Interfaces\Matches\IMatchService;
use App\Interfaces\Tournaments\IQualificationGeneratorService;
use App\Interfaces\Tournaments\ITournamentResultService;
use App\Models\Division;
use App\Repositories\Implementations\TournamentRepository;
use App\Repositories\Interfaces\ITournamentRepository;
use Faker\Factory;
use Illuminate\Support\Facades\DB;


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

    public function __construct(IMatchService $matchService, TournamentRepository $tournamentRepository,
                                ITournamentResultService $tournamentResultService)
    {
        $this->matchService = $matchService;
        $this->tournamentRepository = $tournamentRepository;
        $this->tournamentResultService = $tournamentResultService;
        $this->faker = Factory::create('ru_RU');
    }

    /**
     * Генерация квалификационных матчей для каждого дивизиона
     * @return array Вернем квалификационную таблицу турниров
     */
    public function generateQualificationGames(): array
    {
        // Берем турниры, которые не проводились ранее
        DB::beginTransaction();
        try {
            $tournament = $this->tournamentRepository->getUnsettledTournaments()->first();
            if (!$tournament)
                $tournament = $this->tournamentRepository->createTournament(['name' => 'Чемпионат ' . $this->faker->company]);
            $divisions = Division::all();
            $response = [];

            foreach ($divisions as $division) {
                $row['division'] = $division->id;
                $teams = $division->teams();
                foreach ($teams as $teamHome) {
                    foreach ($teams as $teamGuest) {
                        if ($teamHome->id != $teamGuest->id) {
                            $matchHappened = $this->matchService->getMatchInfoOnTournamentStage($teamHome->id,
                                $teamGuest->id, $tournament->id, 1);
                            if (!$matchHappened) {
                                $match = $this->matchService->addMatchInfo([
                                    'id_division' => $division->id,
                                    'id_team_home' => $teamHome->id,
                                    'id_team_guest' => $teamGuest->id,
                                    'id_tournament' => $tournament->id,
                                    'id_stage' => 1,
                                    'count_goal_team_home' => rand(1, 10),
                                    'count_goal_team_guest' => rand(1, 10)
                                ]);
                                if ($match->count_goal_team_home == $match->count_goal_team_guest) {
                                    $this->tournamentResultService->updateTeamResult($teamGuest->id, $tournament->id, 1);
                                    $this->tournamentResultService->updateTeamResult($teamHome->id, $tournament->id, 1);
                                } else if ($match->count_goal_team_home > $match->count_goal_team_guest) {
                                    $this->tournamentResultService->updateTeamResult($teamHome->id, $tournament->id, 3);
                                } else if ($match->count_goal_team_home < $match->count_goal_team_guest) {
                                    $this->tournamentResultService->updateTeamResult($teamGuest->id, $tournament->id, 3);
                                }
                            }
                            else{

                            }
                        }
                    }
                }
                array_push($response, $row);
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            throw;
        }
        DB::commit();
        return $response;
    }
}
