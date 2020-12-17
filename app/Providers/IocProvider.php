<?php

namespace App\Providers;

use App\Interfaces\Matches\IMatchService;
use App\Interfaces\Tournaments\ITournamentResultService;
use App\Repositories\Implementations\MatchRepository;
use App\Repositories\Implementations\TournamentResultRepository;
use App\Repositories\Interfaces\IMatchRepository;
use App\Repositories\Interfaces\ITournamentResultRepository;
use App\Services\Matches\MatchService;
use App\Services\Tournaments\TournamentResultService;
use Illuminate\Support\ServiceProvider;

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
