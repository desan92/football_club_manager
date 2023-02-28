<?php
//es crea tant el model com la migracio.
//php artisam make:model Club -m


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Club extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_club',
        'escut',
        'email',
        'telefon',
        'ciutat',
        'estadi',
        'founded'
    ];

    //RELACIO 1-1.
    public function user(): HasOne
    {
        //'estructura es aquesta.
        //return $this->hasOne(User::class, 'foreign_key', 'local_key');
        //la foreign_key que s'espera seria el nom del model on estem + _id (club_id)
        //si no es aquesta este de posar despres de la clase com es veu al exemple.
        //de la mateixa manera que el local_key. El que s'espera es id si es un altre este de posar.
        return $this->hasOne(User::class);
    }

    //RELACIO 1-1.
    public function entrenador(): HasOne
    {
        return $this->hasOne(Entrenador::class);
    }

    public function partit(): HasOne
    {
        return $this->hasOne(Partits::class);
    }

    public function jugadors(): HasMany
    {
        return $this->hasMany(Jugador::class);
    }
}
