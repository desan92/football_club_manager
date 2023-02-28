<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partits extends Model
{
    use HasFactory;

    protected $fillable = [
        'resultat',
        'club_local_id',
        'club_visitant_id',
    ];

    public function teamLocal()
    {
        return $this->belongsTo(Club::class,'club_local_id');
    }

    public function teamVisitant()
    {
        return $this->belongsTo(Club::class,'club_visitant_id');
    }
}
