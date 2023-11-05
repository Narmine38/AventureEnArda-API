<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate existing records to start from scratch.
        DB::table('places')->truncate();

        DB::table('places')->insert([
            [
                'name' => 'La Comté',
                'shortDescription' => 'Une région verdoyante habitée par les Hobbits.',
                'longDescription' => 'La Comté est une région de prairies et de collines verdoyantes, avec de nombreux lacs, rivières et forêts, et est connue pour ses trous de hobbit confortables et ses jardins bien entretenus.',
                'locationType' => 'Rural/Campagne',
                'restrictions' => 'Aucune restriction particulière.',
                'travelAdvice' => 'Apportez vos meilleurs habits de fête et préparez-vous à de nombreux repas!',
                'picture' => 'shire.jpg', // Assurez-vous que cette image existe dans votre dossier public/storage
                'story' => 'La Comté est célèbre pour être le point de départ de nombreux aventuriers, y compris Frodon et Bilbon Sacquet.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // ... Ajoutez d'autres lieux ici
        ]);
    }
}
