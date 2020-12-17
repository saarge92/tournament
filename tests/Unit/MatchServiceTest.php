<?php

namespace Tests\Unit;

use App\Interfaces\Matches\IMatchService;
use App\Models\Division;
use App\Models\Stage;
use App\Models\Team;
use App\Models\Tournament;
use App\Repositories\Interfaces\IMatchRepository;
use PHPUnit\Framework\TestCase;

class MatchServiceTest extends TestCase
{

    /**
     * Тестирование метода addMatchInfo
     *
     * @return void
     */
    public function testAddMatch()
    {
        $matchService = $this->getMatchService();
        $matchRepositoryMock = \Mockery::mock(IMatchRepository::class);
        $division = Division::orderByRaw("RAND()")->first();
        $tournament = Tournament::orderByRaw("RAND()")->first();
        $team_home = Team::orderByRaw("RAND()")->first();
        $team_guest = Team::where(['id_team_guest', '!=', $team_home->id]);
        $stage = Stage::orderByRaw("RAND()")->first();
        $countGoalsHome = rand(1, 10);
        $countGoalsGuest = rand(1, 10);

        $data = [
            'id_division' => $division->id,
            'id_tournament' => $tournament->id,
            'id_team_home' => $team_home->id,
            'id_team_guest' => $team_guest->id,
            'id_stage' => $stage->id,
            'count_goal_team_home' => $countGoalsHome,
            'count_goal_team_guest' => $countGoalsGuest
        ];

        $response = $matchService->addMatchInfo($data);
        $matchRepositoryMock->shouldReceive('createMatch')->with($data);
        $this->assertInstanceOf($response, Match::class);

    }

    private function getMatchService(): IMatchService
    {
        return resolve(IMatchService::class);
    }
}
