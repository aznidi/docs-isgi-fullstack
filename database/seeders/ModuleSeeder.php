<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    public function run()
    {
        Module::create([
            'codeMod' => 'WEB101',
            'nomMod' => 'Développement Web',
            'descriptionMod' => 'Introduction au développement web.',
            'anneeMod' => '2023',
        ]);

        Module::create([
            'codeMod' => 'DATA202',
            'nomMod' => 'Analyse des données',
            'descriptionMod' => 'Cours sur l\'analyse et la visualisation de données.',
            'anneeMod' => '2024',
        ]);
    }
}
