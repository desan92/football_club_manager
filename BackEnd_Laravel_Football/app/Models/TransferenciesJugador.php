<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferenciesJugador extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_from_id',
        'club_to_id',
        'jugador_id',
        'created_contract',
        'contract_min'
    ];

    public $timestamps = false;

    public function venedor()
    {
        return $this->belongsTo(Club::class,'club_from_id');
    }

    public function comprador()
    {
        return $this->belongsTo(Club::class,'club_to_id');
    }

    public function jugador()
    {
        return $this->belongsTo(Jugador::class,'jugador_id');
    }
}
