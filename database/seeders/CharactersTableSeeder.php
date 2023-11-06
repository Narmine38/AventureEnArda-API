<?php

namespace Database\Seeders;

use App\Models\Character;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CharactersTableSeeder extends Seeder
{
    /**
     * Exécute le seeder.
     *
     * @return void
     */
    public function run()
    {
        Character::create([
            'name' => 'Frodo Sacquet',
            'story' => 'Un hobbit qui devient le porteur de l’Anneau.',
            'picture' => 'lien_vers_photo_frodo',
            'place_id' => 1
        ]);

    }
}
