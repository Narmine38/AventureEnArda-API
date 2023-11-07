<?php

namespace Database\Seeders;

use App\Models\Accommodation;
use App\Models\Character;
use App\Models\Place;
use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Activite;

class ReservationSeeder extends Seeder
{
    /**
     * Exécute le seeder Reservation.
     */
    public function run(): void
    {
        $user = User::first(); // On prend le premier utilisateur comme exemple
        $place = Place::first(); // On prend le premier lieu comme exemple
        $accommodation = Accommodation::where('place_id', $place->id)->first(); // Premier hébergement de ce lieu
        $activity = Activite::where('place_id', $place->id)->first(); // Première activité de ce lieu
        $character = Character::first(); // Premier personnage comme exemple

        Reservation::create([
            'user_id' => $user->id,
            'place_id' => $place->id,
            'accommodation_id' => $accommodation->id,
            'activity_id' => $activity->id,
            'character_id' => $character->id,
            'arrival_date' => now()->addDays(5),
            'starting_date' => now()->addDays(12),
            'number_of_people' => rand(1, 5),  // Exemple de génération d'un nombre aléatoire entre 1 et 5
            'statut' => 'pending' // Par exemple, pour définir le statut par défaut comme "en attente"
        ]);
    }
}
