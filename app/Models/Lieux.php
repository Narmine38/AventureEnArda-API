<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lieux extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nom de la table associée à ce modèle.
     *
     * @var string
     */
    protected $table = 'lieux';
    protected $fillable = [
        'nom',
        'description',
        'anecdote',
        'photo',
    ];

    // Si vous souhaitez ajouter des relations à d'autres modèles plus tard, vous pouvez le faire ici.
    // Par exemple :
    // public function reservations() {
    //     return $this->hasMany(Reservation::class);
    // }
}
