<?php

namespace Database\Seeders;

use App\Models\RankingTorneo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdatePositionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            '1' => 16,
            '2' => 14,
            '3' => 12,
            '4' => 10,
            '5' => 8,
            '6' => 6,
            '7' => 4,
            '8' => 2,
        ];

        $rankings = RankingTorneo::all();

        foreach ($rankings as $ranking) {
            foreach ($positions as $position => $points) {
                if ($ranking->puntos == $points) {
                    $ranking->position = $position;
                    $ranking->save();
                    break;
                }
            }
        }
    }
}
