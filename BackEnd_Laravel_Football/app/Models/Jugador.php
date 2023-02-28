<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jugador extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'cognom',
        'edat',
        'pais',
        'posicio',
        'forza',
        'valor_mercat',
        'gols',
        'targetes_grogues',
        'targetes_vermelles',
        'nombre_partits',
        'club_id'
    ];

    //1-M (Invers)
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Events_partits::class);
    }
    
}
