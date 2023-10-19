<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hebergement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'description',
        'prix',
        'photo',
        'lieu_id',
    ];

    public function lieu()
    {
        return $this->belongsTo(Lieux::class);
    }

    // Ajoutez d'autres relations si nécessaire
}
