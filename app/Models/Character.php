<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Character extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'story',
        'picture',
        'place_id'
    ];

    /**
     * Récupère le lieu associé au personnage.
     */
    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
