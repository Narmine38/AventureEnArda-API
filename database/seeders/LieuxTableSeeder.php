<?php

namespace Database\Seeders;

use App\Models\Lieux;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LieuxTableSeeder extends Seeder
{

    /**
     * Exécute le seeder.
     *
     * @return void
     */
    public function run()
    {
        Lieux::create([
            'nom' => 'Comté',
            'description' => 'Un endroit paisible habité par les Hobbits.',
            'photo' => 'lien_vers_photo_comte',
            'anecdote' => 'Anecdote sur le Comté.'
        ]);

        // Ajoutez autant d'entrées que nécessaire...
    }
}
