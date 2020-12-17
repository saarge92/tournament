<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DivisionSeeder::class);
        $this->call(StageSeeder::class);
        $this->call(TeamSeeder::class);
        $this->call(TournamentSeeder::class);
    }
}
