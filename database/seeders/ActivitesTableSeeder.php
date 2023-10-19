<?php

namespace Database\Seeders;

use App\Models\Activite;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitesTableSeeder extends Seeder
{
    /**
     * Exécute le seeder.
     *
     * @return void
     */
    public function run()
    {
        Activite::create([
            'nom' => 'Visite de Hobbiton',
            'description' => 'Explorez le village des hobbits.',
            'lieu_id' => 1
        ]);

        // Ajoutez autant d'entrées que nécessaire...
    }
}
