<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /* 
            Passport::tokensExpireIn(now()->addDays(15));
            Passport::refreshTokensExpireIn(now()->addDays(30)); 
            Passport::personalAccessTokensExpireIn(now()->addMonths(6));
        */
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30)); 
        Passport::personalAccessTokensExpireIn(now()->addMonths(2));
        //Passport::routes();
        
        /*
            Access tokens --> porten la informació necessària per accedir directament a un recurs.
            En altres paraules, quan un client passa un testimoni d'accés a un servidor que gestiona un recurs,
            aquest servidor pot utilitzar la informació continguda en el testimoni per decidir si el client està autoritzat o no. 
            Els testimonis d'accés solen tenir una data de caducitat i són de curta durada (tokensExpireIn).

            Refresh tokens --> porten la informació necessària per obtenir un testimoni d'accés nou. En altres paraules, 
            sempre que es requereix un testimoni d'accés per accedir a un recurs específic, un client pot utilitzar un testimoni
            d'actualització per obtenir un testimoni d'accés nou emès pel servidor d'autenticació. Els casos d'ús habituals 
            inclouen obtenir nous testimonis d'accés després que els antics hagin caducat o accedir a un recurs nou per 
            primera vegada. Els testimonis d'actualització també poden caducar, però tenen una vida més llarga (refreshTokensExpireIn).

            La idea darrere dels testimonis d'actualització és que si un testimoni d'accés es veu compromès perquè és de curta durada, 
            l'atacant només té un temps limitat per explotar-lo.

            Els testimonis d'actualització, si es comprometen, són inútils perquè l'atacant requereix l'identificador i el 
            secret del client a més del testimoni d'actualització per obtenir un testimoni d'accés.

            Personal Access Tokens --> s'utilitzen quan els usuaris poden voler generar testimonis d'accés per ells mateixos 
            sense passar pel flux de redirecció estàndard del codi d'autorització. Permetre als usuaris que s'emetin testimonis 
            a ells mateixos mitjançant la interfície d'usuari de la vostra aplicació pot ser útil per permetre que els usuaris 
            juguin amb la vostra API o pot servir com un enfocament més fàcil per emetre fitxes d'accés en general. 
            Aquests testimonis solen tenir una vida llarga (personalAccessTokensExpireIn)

        */
    }
}
