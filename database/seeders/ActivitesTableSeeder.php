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
            'name' => 'Visite de Hobbiton',
            'description' => 'Explorez le village des hobbits.',
            'picture' => 'shire_tour.jpg',
            'type' => 'Visite',
            'age_range' => 'touts ages',
            'place_id' => 1
        ]);

    }
}
