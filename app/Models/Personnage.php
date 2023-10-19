<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personnage extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'nom',
        'histoire',
        'photo',
        'lieu_id'
    ];

    /**
     * Récupère le lieu associé au personnage.
     */
    public function lieu()
    {
        return $this->belongsTo(Lieux::class);
    }
}
