<?php

namespace Database\Seeders;

use App\Models\Personnage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonnagesTableSeeder extends Seeder
{
    /**
     * Exécute le seeder.
     *
     * @return void
     */
    public function run()
    {
        Personnage::create([
            'nom' => 'Frodo Sacquet',
            'histoire' => 'Un hobbit qui devient le porteur de l’Anneau.',
            'photo' => 'lien_vers_photo_frodo',
            'lieu_id' => 1
        ]);

        // Ajoutez autant d'entrées que nécessaire...
    }
}
