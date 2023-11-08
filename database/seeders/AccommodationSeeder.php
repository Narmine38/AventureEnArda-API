<?php

namespace Database\Seeders;

use App\Models\Accommodation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccommodationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Créez quelques hébergements pour 'La Comté'.

        Accommodation::create([
            'name' => 'Hébergement Hobbit de luxe',
            'description' => 'Une belle maison hobbit avec une vue imprenable sur les collines.',
            'type' => 'Maison hobbit',
            'price' => 99.99, // Prix par nuit
            'picture' => 'url_de_l_image', // Remplacez par l'URL réelle de l'image
            'story' => "Une confortable demeure sous terre, avec suffisamment d'espace pour une grande famille hobbit.",
            'place_id' => 1, // Associez à 'La Comté'
        ]);

        Accommodation::create([
            'name' => 'Appartements Elfiques de Rivendell',
            'description' => "Logements élégants avec des vues sur les chutes d'eau et les jardins elfiques.",
            'type' => 'Appartement',
            'price' => 149.99,
            'picture' => 'rivendell_elf.jpg',
            'story' => "Séjournez dans la demeure des Elfes, avec tout le confort et la beauté de Rivendell.",
            'place_id' => 2, // Rivendell

        ]);

    }
}
