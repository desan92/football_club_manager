<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'cognom',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function club1(){
        $club = Club::where('id', $this->club_id)->first();
        return $club;
    }
    //$user->club1();

    //RELACIO 1-1.
    //recordar on hi ha la clau foranea es on hi ha el BelongsTo
    //en aquet cas es la taula users(entrenadors) que te la clau foranea club_id.
    public function club(): BelongsTo
    {
        //return $this->belongsTo(Club::class, 'foreign_key', 'owner_key');
        return $this->belongsTo(Club::class);
    }
}
