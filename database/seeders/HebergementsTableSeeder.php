<?php

namespace Database\Seeders;

use App\Models\Hebergement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HebergementsTableSeeder extends Seeder
{
    /**
     * Exécute le seeder.
     *
     * @return void
     */
    public function run()
    {
        Hebergement::create([
            'nom' => 'Auberge du Poney Fringant',
            'description' => 'Une auberge populaire à Bree.',
            'prix' => 15.00,
            'photo' => 'lien_vers_photo_auberge',
            'lieu_id' => 1  // suppose que le Comté est le premier lieu
        ]);

        // Ajoutez autant d'entrées que nécessaire...
    }
}
