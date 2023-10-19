<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activite extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'nom',
        'description',
        'photo',
        'lieu_id'
    ];

    /**
     * Obtenez le lieu associé à l'activité.
     */
    public function lieu()
    {
        return $this->belongsTo(Lieux::class);
    }
}
