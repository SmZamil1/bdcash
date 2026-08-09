<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\GameSetting::updateOrCreate(
            ['game_key' => 'fortune-maya'],
            [
                'name' => 'Fortune Maya',
                'win_chance' => 30,
                'min_bet' => 1,
                'max_bet' => 500,
                'bet_options' => '1,2,5,10,20,50,100,500',
                'status' => 1,
                'image' => 'fortune_maya.png'
            ]
        );
    }
}
