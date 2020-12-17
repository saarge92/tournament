<?php


use App\Models\Tournament;
use Illuminate\Database\Seeder;

/**
 * Класс-заполнитель для заполнения турниров
 *
 * @package Database\Seeders
 */
class TournamentSeeder extends Seeder
{
    protected array $tournaments = ['Кубок Прибалтики', 'Чемпионат Европы', 'Кубок СНГ'];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->tournaments as $tournament) {
            Tournament::create([
                'name' => $tournament
            ]);
        }
    }
}
