<?php

namespace App\Http\Controllers;

use App\Models\Entrenador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EntrenadorController extends Controller
{
    //funcio que sera utilitzada per obtenir tots els entrenadors de la lliga.
    public function allEntrenadors(){

        //consulta a la db.
        $entrenadors = DB::select('SELECT * FROM entrenadors');

        //resposta que es rep.
        return response()->json([
            'entrenadors' => $entrenadors
        ], 200);

    }

    //funcio que recupera l'entrenador demanat.
    public function onlyEntrenador(){

        $user = Auth::user();
        //consulta a la db.
        //$entrenador = DB::table('entrenadors')->where('id', $id)->get();
        $entrenador = Entrenador::where('club_id', $user->club_id)->first();

        //resposta que es rep.
        return response()->json([
            'entrenador' => $entrenador,
            'club' => $entrenador->club
        ], 200);

    }

    public function entrenadorsClub()
    {
        $user = Auth::user();
        //consulta a la db.
        //$entrenador = DB::table('entrenadors')->where('id', $id)->get();
        $entrenador = Entrenador::where('club_id', $user->club_id)->get();

        //resposta que es rep.
        return response()->json([
            'entrenador' => $entrenador
        ], 200);
    }

    public function entrenadorsDifferentClub()
    {
        $user = Auth::user();
        //consulta a la db.
        //$entrenador = DB::table('entrenadors')->where('id', $id)->get();
        $entrenadors = Entrenador::where('club_id', '!=', $user->club_id)
                                ->orWhereNull('club_id')->paginate(10);

        //resposta que es rep.
        return response()->json([
            'entrenadors' => $entrenadors
        ], 200);
    }

    //perque rebi l'informacio del entrenador pertinent este de posar a la ruta {entrenador} si es posa id o alguna altre cosa
    //no ho reconeixera i no treura l'informacio del entrenador que es vol actualitzar en aquet cas.
    public function actualitzarEntrenador(Request $request, Entrenador $entrenador){

        //amb aquesta comanda s'actualitza els camps que s'ha introduit alguna dada si no hi ha dada es queda igual 
        $entrenador->update($request->all());

        //resposta que es rep un cop feta l'actualitzacio.
        return response()->json([
            'request' => $request->all(),
            'entrenador' => $entrenador
        ], 200);

    }
}
