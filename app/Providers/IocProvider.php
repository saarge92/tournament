<?php

namespace App\Providers;

use App\Interfaces\Matches\IMatchService;
use App\Interfaces\Playoffs\IPlayoffGeneratorService;
use App\Interfaces\Playoffs\IPlayOffService;
use App\Interfaces\Tournaments\IQualificationGeneratorService;
use App\Interfaces\Tournaments\IQualificationTournamentService;
use App\Interfaces\Tournaments\IResultGameFinaleService;
use App\Interfaces\Tournaments\ITournamentResultService;
use App\Repositories\Implementations\MatchRepository;
use App\Repositories\Implementations\ResultFinaleRepository;
use App\Repositories\Implementations\TeamRepository;
use App\Repositories\Implementations\TournamentRepository;
use App\Repositories\Implementations\TournamentResultRepository;
use App\Repositories\Interfaces\IMatchRepository;
use App\Repositories\Interfaces\IResultFinaleRepository;
use App\Repositories\Interfaces\ITeamRepository;
use App\Repositories\Interfaces\ITournamentRepository;
use App\Repositories\Interfaces\ITournamentResultRepository;
use App\Services\Matches\MatchService;
use App\Services\Playoffs\PlayoffGeneratorService;
use App\Services\Playoffs\PlayOffService;
use App\Services\Tournaments\QualificationGeneratorService;
use App\Services\Tournaments\QualificationTournamentService;
use App\Services\Tournaments\TournamentResultFinaleService;
use App\Services\Tournaments\TournamentResultService;
use Illuminate\Support\ServiceProvider;

/**
 * Class IocProvider
 * @package App\Providers
 */
class IocProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(IMatchRepository::class, MatchRepository::class);
        $this->app->singleton(IMatchService::class, MatchService::class);
        $this->app->singleton(ITournamentResultRepository::class, TournamentResultRepository::class);
        $this->app->singleton(ITournamentResultService::class, TournamentResultService::class);
        $this->app->singleton(ITournamentRepository::class, TournamentRepository::class);
        $this->app->singleton(ITeamRepository::class, TeamRepository::class);
        $this->app->singleton(IResultFinaleRepository::class, ResultFinaleRepository::class);
        $this->app->singleton(IResultGameFinaleService::class, TournamentResultFinaleService::class);
        $this->app->singleton(IQualificationGeneratorService::class, QualificationGeneratorService::class);
        $this->app->singleton(IQualificationTournamentService::class, QualificationTournamentService::class);
        $this->app->singleton(IPlayoffGeneratorService::class, PlayoffGeneratorService::class);
        $this->app->singleton(IPlayOffService::class, PlayOffService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
