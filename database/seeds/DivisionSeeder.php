<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

/**
 * Класс-наполнитель для заполнения данных о дивизионах
 *
 * @package Database\Seeders
 */
class DivisionSeeder extends Seeder
{
    protected array $divisions = ['A', 'B'];

    /**
     * Процесс запуска наполнителя
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->divisions as $division) {
            Division::create([
                'name' => $division
            ]);
        }
    }
}
