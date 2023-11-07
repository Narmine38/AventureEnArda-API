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
        'place_id',
        'accommodation_id',
        'activity_id',
        'character_id',
        'arrival_date',
        'starting_date',
        'number_of_people',
        'statut',
        'price', // Ensure 'price' is mass assignable
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
    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

    /**
     * Obtient l'activité associée à la réservation.
     */
    public function activity()
    {
        return $this->belongsTo(Activite::class);
    }

    /**
     * Obtient le personnage associé à la réservation.
     */
    public function character()
    {
        return $this->belongsTo(Character::class);
    }
    public function place()
    {
        return $this->belongsTo(Place::class);
    }
    public function calculatePrice($nights, $numberOfPeople, $accommodationPrice)
    {
        // Assuming $accommodationPrice is passed in or retrieved from related Accommodation
        $this->price = $accommodationPrice * $nights * $numberOfPeople;
    }

}

