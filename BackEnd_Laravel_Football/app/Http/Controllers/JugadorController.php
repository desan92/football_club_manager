<?php

namespace App\Http\Controllers;

use App\Http\Requests\JugadorRequest;
use App\Models\Club;
use App\Models\Jugador;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JugadorController extends Controller
{
    //funcio que retorna tots els jugadors registrats a la db.
    public function allJugadors(){

        //consulta a la db per que et retorni tots els jugadors 
        $jugadors = DB::select('SELECT * FROM jugadors');

        //resposta que es retorna en cas de sortir tot bé.
        return response()->json(['jugadors' => $jugadors], 200);

    }
    //funcio que recupera l'entrenador demanat.
    public function onlyJugador($id){

        //consulta a la db.
        $jugador = Jugador::where('id', $id)->first();

        //resposta que es rep.
        return response()->json([
            'jugador' => $jugador
        ], 200);

    }

    //funcio que retorna tots els jugadors registrats a la db pero que tenen el camp club_id null i 
    //per tant son considerats agents lliures.
    public function jugadorsLliures(){

        //consulta a la db que retorna amb el wherenull els valors null del camp club_id.
        $jugadors = DB::table('jugadors')->whereNull('club_id')->get();

        //resposta que es retorna en cas de sortir tot bé.
        return response()->json([
            'jugadors' => $jugadors
        ], 200);

    }

    public function jugadorsDiferentClub($id){

        //consulta a la db que retorna amb el wherenull els valors null del camp club_id.
        $jugadors = DB::table('jugadors')->where('club_id', '!=', $id)
                                        ->orWhereNull('club_id')->paginate(10);

        //resposta que es retorna en cas de sortir tot bé.
        return response()->json([
            'jugadors' => $jugadors

        ], 200);

    }

    //funcio utilitzada per obtenir tots els jugadors que te un equip en particular.
    public function clubJugadors(Club $club){

        //consulta a la db que retorna amb el where els jugadors que te aquell equip en concret.
        //$jugadors = DB::table('jugadors')->where('club_id', $club->id)->get();
        //aixo seria utilitzant el metode eloquent de belongsTo.
        $jugadors = Jugador::where('club_id', $club->id)->paginate(10);

        //resposta que es retorna en cas de sortir tot bé.
        return response()->json([
            'jugadors' => $jugadors,
            'club' => $club
            ],
             200);
    }

    //funcio utilitzada per crear un nou jugador, probablement anira a la funcio de sortir de la cantera.
    public function nouJugador(){

        //es validen els valors de la request amb les dades entrades a JugadorRequest
        //$request->validated();

        $name = DB::table('firstNames')
                    ->inRandomOrder()->first();

        $lastname = DB::table('lastname')
                    ->inRandomOrder()->first();

        $pais = DB::table('paises')
                    ->inRandomOrder()->first();

        //aixo es per al camp aleatori de posicio del jugador a crear.
        $position = ['POR', 'DEF', 'MC', 'DEL'];
        $random_key = array_rand($position, 1);

        //es crea el nou jugador i s'introdueix a la db.
        $jugador = Jugador::create([
            'nom' => $name->name,
            'cognom' => $lastname->surname,
            'edat' => random_int(16, 19), 
            'pais' => $pais->pais,
            'posicio' => $position[$random_key],
            'forza' => random_int(50, 100), 
            'valor_mercat' => random_int(5, 20), 
            'gols' => 0,
            'targetes_grogues' => 0,
            'targetes_vermelles' => 0,
            'nombre_partits' => 0,
            'club_id' => Auth::user()->club_id
        ]);

        //es retorna una resposta.
        return response()->json(['jugador' => $jugador,], 200);

    }

    //funcio que s'utilitzara per a borrar jugadors que no ens interesin.
    public function borrarJugador($id){

        try{

            //aqui agafem el valor del club del usuari registrat.
            $user_club_id = Auth::user()->club_id;

            //aqui busquem apartir del club del usuari i l'id si i trobem alguna resposta.
            $jugador = DB::table('jugadors')->where('club_id', $user_club_id)->where('id', $id)->get();
            
            //si no ve cap resposta es llença un error perque l'array es buit.
            if(sizeof($jugador) == 0)
            {
                return response()->json('Jugador no trobat', 404);
            }

            //amb aquesta comanda eliminem el jugador si cumpleix la condicio de que el que l'elimina es el
            //president del seu club.
            DB::table('jugadors')->where('id', $id)->delete();

            //en canvi si es troba un jugador que coincideixi et retornara una resposta positiva.
            return response()->json(
                [
                    'id' => $id,
                    'user_club_id' => $user_club_id,
                    'jugador' => $jugador
                ],
                 200);

        } catch(Exception $e){
            //en cas de que sorgis algun error pasa per el catch.
            return response()->json($e, 400);
        }
        
    }

    //perque rebi l'informacio del jugador pertinent este de posar a la ruta {jugador} si es posa id o alguna altre cosa
    //no ho reconeixera i no treura l'informacio del jugador que es vol actualitzar en aquet cas.
    public function actualitzarJugador(Request $request, Jugador $jugador){

        //amb aquesta comanda s'actualitza els camps que s'ha introduit alguna dada si no hi ha dada es queda igual 
        $jugador->update($request->all());

        //resposta que es rep un cop feta l'actualitzacio.
        return response()->json([
            'request' => $request->all(),
            'jugador' => $jugador
        ], 200);

    }

    

}
