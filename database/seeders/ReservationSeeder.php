<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Lieux;
use App\Models\Hebergement;
use App\Models\Activite;
use App\Models\Personnage;

class ReservationSeeder extends Seeder
{
    /**
     * Exécute le seeder Reservation.
     */
    public function run(): void
    {
        $user = User::first(); // On prend le premier utilisateur comme exemple
        $lieu = Lieux::first(); // On prend le premier lieu comme exemple
        $hebergement = Hebergement::where('lieu_id', $lieu->id)->first(); // Premier hébergement de ce lieu
        $activite = Activite::where('lieu_id', $lieu->id)->first(); // Première activité de ce lieu
        $personnage = Personnage::first(); // Premier personnage comme exemple

        Reservation::create([
            'user_id' => $user->id,
            'lieu_id' => $lieu->id,
            'hebergement_id' => $hebergement->id,
            'activite_id' => $activite->id,
            'personnage_id' => $personnage->id,
            'date_arrivee' => now()->addDays(5),
            'date_depart' => now()->addDays(12),
            'nombre_personnes' => rand(1, 5),  // Exemple de génération d'un nombre aléatoire entre 1 et 5
            'statut' => 'pending' // Par exemple, pour définir le statut par défaut comme "en attente"
        ]);
    }
}
