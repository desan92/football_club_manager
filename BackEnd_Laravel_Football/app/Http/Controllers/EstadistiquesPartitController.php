<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Events_partits;
use App\Models\Jugador;
use App\Models\Partits;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class EstadistiquesPartitController extends Controller
{
    ////funcio que agafa l'informacio del events_partits escollit.
    public function gameStats($id){

        $game = Partits::where('id', $id)
                        ->first();
        $local = Club::where('id', $game->club_local_id)->first();
        $visitant = Club::where('id', $game->club_visitant_id)->first();
        $stats = Events_partits::where('partit_id', $id)->get();
        //$jugador = Jugador::where('jugador_id', $game->jugador_id)->get();

        for($i = 0; $i < sizeof($stats); $i++)
        {
            $jugador = Jugador::where('id', $stats[$i]->jugador_id)->first();
            $stats[$i]->jugador = $jugador;
        }

        return response()->json([
            'partit' => $game,
            'equipLocal' => $local,
            'equipVisitant' => $visitant,
            'estadistiques' => $stats,

        ], 200);
        
    }

    //funcio que crea el partit.
    public function creategameStats(Request $request){

        //validacio de les dades entrades.
        $validated = $request->validate([
            'partit_id' => 'required'
        ]);

        //es comprova que l'usuari que vol fer aquesta accio esta loggejat.
        if(!Auth::user())
        {
            return response()->json([
                'msg' => 'No tens permis per fer aquesta accio.'
            ], 404);
        }
        
        //s'agafan les dades del partit que es vol crear els seus esdeveniments
        $game = DB::table('partits')->where('id', $request->partit_id)->first();

        $local = $this->comprovacioEquipJugadors($game->club_local_id);
        $visitant = $this->comprovacioEquipJugadors($game->club_visitant_id);

        if(!$local || !$visitant)
        {
            return response()->json([
                'msg' => 'Un dels dos equips no te prous jugadors'
            ], 404);
        }

        //s'obtenen els jugadors dels dos equips que disputaran aquet partit.
        $local_team_jugadors = DB::table('jugadors')->where('club_id', $game->club_local_id)->get();
        $visitant_team_jugadors = DB::table('jugadors')->where('club_id', $game->club_visitant_id)->get();

        $local7Titular = $this->selectPlayers(json_decode(json_encode($local_team_jugadors), true));
        $this->partitJugat($local7Titular);
        $visitant7Titular = $this->selectPlayers(json_decode(json_encode($visitant_team_jugadors), true));
        $this->partitJugat($visitant7Titular);

        $stats = $this->stats($local7Titular, $visitant7Titular, $request->partit_id);

        $this->insertStats($stats);
        $resultat = $this->resultat($request->partit_id, $game->club_local_id);
        $partit = Partits::find($request->partit_id);
        $partit->update(['resultat' => $resultat]); 

        $this->updateJugador($stats);


        return response()->json([
            'partit' => $partit,
            'equiptitularlocal' => $local7Titular,
            'equiptitularvisitant' => $visitant7Titular,
            'stats' => $stats,
            'resultat' => $resultat
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

    //es seleciona l'equip titular
    private function selectPlayers($team){

        $whilei = 0;
        $i = 0;
        $teamTemp = array();
        $porters = $this->forJugadors($team, "POR");
        $defenses = $this->forJugadors($team, "DEF");
        $mitjos = $this->forJugadors($team, "MC");
        $delenters = $this->forJugadors($team, "DEL");
        
        while($whilei < 4)
        {
            if($whilei === 0)
            {
                $size = sizeof($porters);
                $randomNum = random_int(0, $size-1);
                $numVoltes = 1;
                $whilearray = $porters;
            }
            elseif($whilei === 1)
            {
                $size = sizeof($defenses);
                $randomNum = random_int(0, $size-1);
                $numVoltes = 3;
                $whilearray = $defenses;
            }
            elseif($whilei === 2)
            {
                $size = sizeof($mitjos);
                $randomNum = random_int(0, $size-1);
                $numVoltes = 2;
                $whilearray = $mitjos;
            }
            elseif($whilei === 3)
            {
                $size = sizeof($delenters);
                $randomNum = random_int(0, $size-1);
                $numVoltes = 1;
                $whilearray = $delenters;
            }

            //posibles errors que el size sigui -1 perque l'array es buit.
            if($size === 0)
            {
                return response()->json([
                    'msg' => "No tens jugadors d'alguna posicio."
                ], 404);
            }
            
            //no hi hagui prous jugadors en una posicio per fer les voltes del numVoltes.
            if($numVoltes > $size)
            {
                return response()->json([
                    'msg' => "D'alguna posicio no tens prous jugadors tens de poder fer un 1-3-2-1"
                ], 404);
            }

            while($i < $numVoltes)
            {
                //afegeixes el valor al nou array per fer l'equip.
                array_push($teamTemp, $whilearray[$randomNum]);

                //es borra l'element que s'ha afefit al array del equip titulat per no repetir jugador
                unset($whilearray[$randomNum]);
                $reindex = array_values($whilearray);

                //s'iguala l'array d'ajuda amb l'array que conte els jugadors.
                $whilearray = $reindex;
                
                //es restableix el valor del size del array que fa d'auxiliar. Per que no surti el random.
                $size = sizeof($whilearray);
                $randomNum = random_int(0, $size-1);

                $i++;
            }
            
            $whilei++;
            $i = 0;

        }

        return json_decode(json_encode($teamTemp));
    }

    //for per obtenir els jugadors de l'equip per posicio.
    private function forJugadors($team, $posicio){

        $posicio_arr = array();

        for($i = 0; $i < sizeof($team); $i++)
        {
            if($team[$i]['posicio'] === $posicio)
            {
                array_push($posicio_arr, $team[$i]);
            }
        }

        return $posicio_arr;

    }

    //$local, $visitant
    private function stats($local, $visitant, $partit){

        $i = 0;
        $random = random_int(15, 20);
        $finalStats = array();
        
        $stats = [
            "Parada",
            "El porter s'ha lluit en aquesta accio",
            "Gol",
            "Surt rozan el pal",
            "Targeta groga",
            "Falta en una posicio prometedora",
            "Falta, es talla el contraactac",
            "Corner",
            "Penalti.... Falla el penalti",
            "Penalti.... Gol"
        ];

        while($i < $random){
            $object = new stdClass();
            $randomLocalOrVisitant = random_int(0, 1);
            $randomStats = random_int(0, 9);
            $random_jugador = random_int(0, 6);

            

            if($randomLocalOrVisitant === 0)
            {
                //local
                $object->local = "Equip Local";
                $object->esdeveniment = $stats[$randomStats];

                if($stats[$randomStats] === "Parada" || $stats[$randomStats] === "El porter s'ha lluit en aquesta accio")
                {
                    $porters = $this->forJugadors(json_decode(json_encode($local), true), "POR");
                    $porters = json_decode(json_encode($porters));
                    $object->jugador = $porters[0]->id;
                }
                else
                {
                    $object->jugador = $local[$random_jugador]->id;
                }
                
                $object->equip = $local[$random_jugador]->club_id;
                $object->partit_id = $partit;

            }
            else
            {
                //visitant
                $object->visitant = "Equip Visitant";
                $object->esdeveniment = $stats[$randomStats];

                if($stats[$randomStats] === "Parada" || $stats[$randomStats] === "El porter s'ha lluit en aquesta accio")
                {
                    $porters = $this->forJugadors(json_decode(json_encode($visitant), true), "POR");
                    $porters = json_decode(json_encode($porters));
                    $object->jugador = $porters[0]->id;
                }
                else
                {
                    $object->jugador = $visitant[$random_jugador]->id;
                }

                $object->equip = $visitant[$random_jugador]->club_id;
                $object->partit_id = $partit;
            }
            
            array_push($finalStats, $object);
            $i++;
        }

        return $finalStats;

    }

    //insert dades dels events del partit.
    private function insertStats($stats){

        $i = 0;
        while($i < sizeof($stats))
        {
            $esdeveniment = Events_partits::create([
                'esdeveniment' => $stats[$i]->esdeveniment,
                'jugador_id' => $stats[$i]->jugador,
                'club_id' => $stats[$i]->equip,
                'partit_id' => $stats[$i]->partit_id,
            ]);

            //return $this->updateJugador($stats[$i]);

            $i++;
        }

        return true;

    }

    //actualitzacio del resultat taula partits.
    private function resultat($id, $local){

        $var_primer_equip = '';
        $golsequip1 = 0;
        $golsequip2 = 0;
        $golslocal = 0;
        $golsvisitant = 0;

        $resultat = DB::table('events_partits')->where('partit_id', $id)
                                           ->where('esdeveniment', 'LIKE', "%Gol%")
                                           ->get();

        for($i = 0; $i < sizeof($resultat); $i++)
        {
            $var_primer_equip = $resultat[0]->club_id;

            if($var_primer_equip === $resultat[$i]->club_id)
            {
                $golsequip1++;
            }
            else
            {
                $golsequip2++;
            }
        }

        if($local === $resultat[0]->club_id)
        {
            $golslocal = $golsequip1;
            $golsvisitant = $golsequip2;
        }
        else
        {
            $golslocal = $golsequip2;
            $golsvisitant = $golsequip1;
        }

        $resultatFinal = $golslocal . '-' . $golsvisitant;

        return $resultatFinal;

    }

    private function updateJugador($stats){

        
        $gol = 0;
        $goltrue = false;
        $targeta = 0;
        $targetatrue = false;
        $whileY = 0;

        while($whileY < sizeof($stats))
        {
            if($stats[$whileY]->esdeveniment === 'Gol' || $stats[$whileY]->esdeveniment === 'Penalti.... Gol')
            {
                $gol++;
                $goltrue = true;
            }

            if($stats[$whileY]->esdeveniment === 'Targeta groga')
            {
                $targeta++;
                $targetatrue = true;
            }

            $jugador = Jugador::find($stats[$whileY]->jugador);
            //$jugador->update(['resultat' => $stats->jugador_id]);

            if($goltrue && $targetatrue)
            {
                $jugador->gols += 1;
                $jugador->targetes_grogues += 1;
                $jugador->save();

            }
            elseif(!$goltrue && $targetatrue)
            {
                $jugador->targetes_grogues += 1;
                $jugador->save();
                
            }
            elseif($goltrue && !$targetatrue)
            {
                $jugador->gols += 1;
                $jugador->save();
                
            }

            $gol = 0;
            $goltrue = false;
            $targeta = 0;
            $targetatrue = false;
            $whileY++;
        }
        

    }

    private function partitJugat($team){  

        for($i = 0; $i < sizeof($team); $i++)
        {
            $jugador = Jugador::find($team[$i]->id);
            $jugador->nombre_partits += 1;
            $jugador->save();
        }
        
    }

}
