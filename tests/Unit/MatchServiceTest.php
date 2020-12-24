<?php

namespace Tests\Unit;

use App\Interfaces\Matches\IMatchService;
use App\Models\Division;
use App\Models\Match;
use App\Models\Stage;
use App\Models\Team;
use App\Models\Tournament;
use App\Providers\IocProvider;
use App\Repositories\Interfaces\IMatchRepository;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;

/**
 * Unit тестирование для функционала класса MatchService
 * @package Tests\Unit
 */
class MatchServiceTest extends TestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->createApplication()->register(IocProvider::class);
    }

    /**
     * Тестирование метода addMatchInfo в сервисе MatchService
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
        $team_guest = Team::whereRaw("id != ?", [$team_home->id])->first();
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
        $this->assertInstanceOf(Match::class, $response);

    }

    /**
     * Получение зависимости по работе с матчами
     * @return IMatchService
     */
    private function getMatchService(): IMatchService
    {
        return resolve(IMatchService::class);
    }
}
