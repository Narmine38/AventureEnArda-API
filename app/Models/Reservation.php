<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Les attributs pouvant être assignés massivement (en anglais, "mass assignable").
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'lieu_id',
        'hebergement_id',
        'activite_id',
        'personnage_id',
        'date_arrivee',
        'date_depart',
        'nombre_personnes',  // Ajouté ici
        'statut',  // Ajouté ici
    ];

    /**
     * Obtient l'utilisateur associé à la réservation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtient l'hébergement associé à la réservation.
     */
    public function hebergement()
    {
        return $this->belongsTo(Hebergement::class);
    }

    /**
     * Obtient l'activité associée à la réservation.
     */
    public function activite()
    {
        return $this->belongsTo(Activite::class);
    }

    /**
     * Obtient le personnage associé à la réservation.
     */
    public function personnage()
    {
        return $this->belongsTo(Personnage::class);
    }
    public function lieu()
    {
        return $this->belongsTo(Lieux::class);
    }
    public function getPriceAttribute()
    {
        $nights = (new \DateTime($this->date_arrivee))->diff(new \DateTime($this->date_depart))->days;

        $hebergementCost = $this->hebergement->prix * $nights * $this->nombre_personnes;

        return $hebergementCost;
    }

}

