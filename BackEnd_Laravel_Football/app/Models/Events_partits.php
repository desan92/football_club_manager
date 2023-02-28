<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Events_partits extends Model
{
    use HasFactory;

    protected $fillable = [
        'esdeveniment',
        'jugador_id',
        'club_id',
        'partit_id'
    ];

    //1-M (Invers)
    public function jugador(): BelongsTo
    {
        return $this->belongsTo(Jugador::class);
    }

    public function partit()
    {
        return $this->belongsTo(Partit::class);
    }

    public function teams()
    {
        return $this->belongsTo(Club::class);
    }
}
