<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Entrenador;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClubController extends Controller
{
    //funcio que sera utilitzada per obtenir tots els clubs de la lliga.
    public function allClubs(){

        //consulta a la db.
        $clubs = DB::select('SELECT * FROM clubs');

        //resposta que es rep.
        return response()->json([
            'clubs' => $clubs
        ], 200);

    }

    //funcio que recupera el club demanat.
    public function onlyClub($id){

        //consulta a la db.
        //$user = Auth::user();
        $club = DB::table('clubs')->where('id', $id)->first();
        $user = Club::find($id)->user;
        $manager = Club::find($id)->entrenador;
        $jugador = Club::find($id)->jugadors()->paginate(10);

        //resposta que es rep.
        return response()->json([
            'club' => $club,
            'user' => $user,
            'manager' => $manager,
            'jugadors' => $jugador
        ], 200);

    }

    //perque rebi l'informacio del club pertinent este de posar a la ruta {club} si es posa id o alguna altre cosa
    //no ho reconeixera i no treura l'informacio del club que es vol actualitzar en aquet cas.
    public function actualitzarClub(Request $request, Club $club){

        //amb aquesta comanda s'actualitza els camps que s'ha introduit alguna dada si no hi ha dada es queda igual 
        $club->update($request->all());

        //resposta que es rep un cop feta l'actualitzacio.
        return response()->json([
            'request' => $request->all(),
            'club' => $club
        ], 200);

    }

}
