<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartitRequest;
use App\Models\Club;
use App\Models\Jugador;
use App\Models\Partits;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PartitController extends Controller
{
    //funcio que recull tots els partits que ha fet un club.
    public function allgameClub(Club $club){

        //part comentada es la que esta feta amb el db i consultes a altres taules dins el for per
        //obtenir els noms dels equips local i visitant.
        /*$games = DB::table('partits')
                        ->where('club_local_id', $club->id)
                        ->orWhere('club_visitant_id', $club->id)->get();*/

        //recordar que si es vol fer servir el model este de posar a la priemra consulta si es fa al db no ho podre acoplar.
        $games = Partits::where('club_local_id', $club->id)->orWhere('club_visitant_id', $club->id)->get();

        for($i = 0; $i < sizeof($games); $i++)
        {
            //$local = DB::table('clubs')->select('nom_club')->where('id', $games[$i]->club_local_id)->first();
            //$games[$i]->club_local_id = $local->nom_club;
            $games[$i]->club_local_id = $games[$i]->teamLocal->nom_club;
            //$visitant = DB::table('clubs')->select('nom_club')->where('id', $games[$i]->club_visitant_id)->first();
            //$games[$i]->club_visitant_id = $visitant->nom_club;
            $games[$i]->club_visitant_id = $games[$i]->teamVisitant->nom_club;
        }

        return response()->json([
            'partits' => $games,
        ], 200);

    }

    //funcio que agafa l'informacio del partit escollit.
    public function gameClub($id){

        $game = DB::table('partits')->where('id', $id)->get();

        return response()->json([
            'partit' => $game
        ], 200);
        
    }

    public function comprovacioEquipJugadors($id){

        $jugadors = Jugador::where('club_id', $id)->get();
        $okTeam = true;
        $posicions = ["POR", "DEF", "MC", "DEL"];
        $whiley = 0;
        $contador = 0;
        $por = 1;
        $def = 3;
        $mc = 2;
        $del = 1;
        $auxvarPosicio = 0;

        while($whiley < sizeof($posicions))
        {
            //es dona al valor de referencia de la posicio a la variable auxiliar.
            if($posicions[$whiley] === "POR")
            {
                $auxvarPosicio = $por;
            }
            elseif($posicions[$whiley] === "DEF")
            {
                $auxvarPosicio = $def;
            }
            elseif($posicions[$whiley] === "MC")
            {
                $auxvarPosicio = $mc;
            }
            elseif($posicions[$whiley] === "DEL")
            {
                $auxvarPosicio = $del;
            }

            //es busca el nombre de jugadors per posicio.
            for($i = 0; $i < sizeof($jugadors); $i++)
            {
                if($posicions[$whiley] === $jugadors[$i]->posicio)
                {
                    $contador++; 
                }
            }

            //es retorna un bolean depenent del nombre de jugadors per posicio.
            if($auxvarPosicio > $contador)
            {
                $okTeam = false;
                break;
            }

            $whiley++;
            $contador = 0;
        }

        return  $okTeam;

    }

    //funcio que crea el partit.
    public function creategame(PartitRequest $request){

        try{

            //validacio del request.
            $request->validated($request->all());

            //comprovacio que l'usuari i el club  local son el mateix.
            if(Auth::user()->club_id != $request->club_local_id)
            {
                return response()->json([
                    'msg' => 'Aquest no es el teu club i no pots fer aquesta operacio. Mira que siguis local.'
                ], 404);
            }

            //comprovacio que els dos clubs son diferents.
            if($request->club_local_id === $request->club_visitant_id)
            {
                return response()->json([
                    'msg' => 'Tria un club visitant diferent al teu.'
                ], 404);
            }

            $local = $this->comprovacioEquipJugadors($request->club_local_id);
            $visitant = $this->comprovacioEquipJugadors($request->club_visitant_id);

            if(!$local || !$visitant)
            {
                return response()->json([
                    'msg' => 'Un dels dos equips no te prous jugadors'
                ], 404);
            }

            //es crea el partit en questio.
            $game = Partits::create([
                'club_local_id' => $request->club_local_id,
                'club_visitant_id' => $request->club_visitant_id
            ]);

            //es genera una resposta 
            return response()->json([
                'msg' => 'partit creat',
                'partit' => $game
            ], 200);
            
        }catch(Exception $e){

            return response()->json([
                    'error' => $e, 
                ], 400);
        }

    }
}
