<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RankingTorneo;

class UpdateRankingTorneoSeeder extends Seeder
{
    public function run()
    {
        $rankings = RankingTorneo::all();

        foreach ($rankings as $ranking) {

            $ranking->update(['año' => (string) '2023-2024']);
        }
    }
}
