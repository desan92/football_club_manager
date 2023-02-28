<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entrenador extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'cognom',
        'edat',
        'pais',
        'club_id'
    ];

    public function club(): BelongsTo
    {
        //return $this->belongsTo(Club::class, 'foreign_key', 'owner_key');
        return $this->belongsTo(Club::class, 'club_id', 'id');
    }
}
