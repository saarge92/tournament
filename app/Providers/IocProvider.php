<?php

namespace App\Providers;

use App\Interfaces\Matches\IMatchService;
use App\Repositories\Implementations\MatchRepository;
use App\Repositories\Interfaces\IMatchRepository;
use App\Services\Matches\MatchService;
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
