<?php

namespace Database\Seeders;

use App\Models\Stage;
use Illuminate\Database\Seeder;

class StageSeeder extends Seeder
{
    protected array $stages = [
        1 => 'Qualification',
        2 => 'Quarterfinal',
        3 => 'Semifinal',
        4 => 'Third place',
        5 => 'Final'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->stages as $index => $stage) {
            $stageExist = Stage::find($index);
            if (!$stageExist) {
                Stage::create([
                    'id' => $index,
                    'name' => $stage
                ]);
            }
        }
    }
}
