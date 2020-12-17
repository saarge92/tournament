<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

/**
 * Класс-наполнитель для создания команд
 *
 * @package Database\Seeders
 */
class TeamSeeder extends Seeder
{
    protected array $teamsDivisionA = [
        'Россия', 'Латвия', 'Эстония', 'Литва', 'Польша', 'Словакия',
    ];
    protected array $teamDivisionB = [
        'Швеция', 'Финляндия', 'Норвегия', 'Дания', 'Швейцария', 'Германия'
    ];

    /**
     * Процесс запуска наполнителя
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->teamsDivisionA as $team) {
            Team::create([
                'name' => $team,
                'id_division' => 1
            ]);
        }

        foreach ($this->teamDivisionB as $team) {
            Team::create([
                'name' => $team,
                'id_division' => 2
            ]);
        }
    }
}
