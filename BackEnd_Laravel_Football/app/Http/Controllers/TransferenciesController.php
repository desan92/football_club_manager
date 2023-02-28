<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferenciaEntrenadorRequest;
use App\Http\Requests\TransferenciaJugadorsRequest;
use App\Models\Club;
use App\Models\Entrenador;
use App\Models\Jugador;
use App\Models\TransferenciesEntrenador;
use App\Models\TransferenciesJugador;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransferenciesController extends Controller
{
    //jugador

    //es consulta a la db taula transferencies_jugadors i s'obtenen tots els resultats.
    public function allTransferenciesJugadorall(){

        $jugadors = DB::table('transferencies_jugadors')->get();

        return response()->json([
            'jugadors' => $jugadors
        ], 200);

    }

    //consulta de totes les transferencies que ha tingut un jugador.
    public function allTransferenciesJugador(Jugador $jugador){

        $jugador_transferencies = DB::table('transferencies_jugadors')->where('jugador_id', $jugador->id)->get();

        return response()->json([
            'jugador' => $jugador_transferencies
        ], 200);

    }

    //consulta de totes les transferencies que ha fet un club.
    public function allTransferenciesJugadorclub(Club $club){

        $jugador_transferencies = TransferenciesJugador::where('club_from_id', $club->id)
                                            ->orWhere('club_to_id', $club->id)->paginate(10);

        for($i = 0; $i < sizeof($jugador_transferencies); $i++)
        {
            if($jugador_transferencies[$i]->club_from_id !== null)
            {
                $jugador_transferencies[$i]->club_from_id = $jugador_transferencies[$i]->venedor;
            }

            if($jugador_transferencies[$i]->club_to_id !== null)
            {
                $jugador_transferencies[$i]->club_to_id = $jugador_transferencies[$i]->comprador;
            }

            $jugador_transferencies[$i]->jugador_id = $jugador_transferencies[$i]->jugador;

        }

        return response()->json([
            'jugador' => $jugador_transferencies
        ], 200);

    }

    //funcio per comprar un jugador nou
    public function compraJugador(TransferenciaJugadorsRequest $request){

        try{
            
            //validacio de l'informacio arribada per el request.
            $request->validated($request->all());

            //es comprova que l'usuari actiu pertany al club que vol der l'operacio de compra.
            if(Auth::user()->club_id != $request->club_to_id)
            {
                return response()->json([
                    'msg' => "Aquest usuari no pot fer aquesta accio."
                ], 404);
            }

            //es busca a la db el jugador per id.
            $jugador_fixatge = Jugador::find($request->jugador_id);   

            //comprovar l'equip que esta el jugador.
            //si es igual salta error ja que si esta al mateix equip no hi ha traspas.
            if($jugador_fixatge->club_id == $request->club_to_id)
            {
                //s'envia un error per veure que aquet jugador ja pertany aquet club.
                return response()->json([
                    'msg' => "El jugador ja esta al teu equip"
                ], 404);
                
            }
            else
            {
                //en el cas de no estar al mateix equip. S'afegeix a la request la variable jugador_club_id amb el valor corresponent.
                $request->request->add(['jugador_club_id' => $request->club_to_id]);
            }

            //es mira l'ultima transferencia feta per el jugador i es comprova apartir d'aqui amb la data de contracte.
            $transferencia = TransferenciesJugador::select()
                                    ->where('jugador_id', $request->jugador_id)->orderBy('id', 'desc')->first();

            //es per veure el dia actual en que ens trobem.
            $currentDateTime = Carbon::today()->toDateString();

            //primer es mira que la transferencia rebuda no sigui null ja que si es null vol dir que es un jugador que no ha tingut mai cap equip.
            if($transferencia !=  null)
            {
            //si aquesta data es superior a l'actual no es podra fixar el jugador.
                if($transferencia->contract_min > $currentDateTime)
                {
                    //s'envia un error per veure que aquesta operacio no es pot fer perque esta en contracte o periode de gracia amb l'equip que ha firmat.
                    return response()->json([
                                'contract' => $transferencia->contract_min,
                                'dateTime' => $currentDateTime,
                                'msg' => "Periode que no es pot fitxar l'entrenador"
                            ], 404);
                }
            }

            //aqui comprarias el jugador.
            return $this->createTransferenciesJugador($request);

        }
        catch(Exception $e){
            //en cas de que sorgis algun error pasa per el catch.
            return response()->json([
                    'error' => $e, 
                ], 400);
            }
        
    }

    //funcio per vendre l'entrenador que tens en plantilla.
    public function ventaJugador(TransferenciaJugadorsRequest $request){

        try{

            //validacio de les dades del $request.
            $request->validated($request->all());

            //es comprova que l'usuari es el mateix del club que vol fer l'operacio de venta.
            if(Auth::user()->club_id != $request->club_from_id)
            {
                return response()->json([
                    'request' => $request->all(),
                    'auth' => Auth::user()->club_id,
                    'club_from_id' => $request->club_from_id,
                    'msg' => "Aquest usuari no pot fer aquesta accio."
                ], 404);
            }

            //es mira quin equip es troba l'entrenador 
            $jugador = Jugador::select()
                                    ->where('id', $request->jugador_id)->first();
            
            //es comprova que no sigui null ja que si es null es un agent lliure no te equip
            if($jugador !=  null)
            {
            //si aquesta data es diferent al jugador registrat salta el missatge.
                if($jugador->club_id != $request->club_from_id)
                {
                    return response()->json([
                                'jugador' => $jugador,
                                'msg' => "Aquet jugador no es el teu, per tant no el pots vendre."
                            ], 404);
                }
            }
            
            return $this->createTransferenciesJugador($request);

        }
        catch(Exception $e){
            //en cas de que sorgis algun error pasa per el catch.
            return response()->json($e, 400);
        }
        
    }

    private function createTransferenciesJugador(Request $request){

        //comprovacio transferencia
        $currentDateTime = Carbon::today()->toDateString();
        $newDateTime = Carbon::today()->addDays(5)->toDateString();

        TransferenciesJugador::create([
            'club_from_id' => $request->club_from_id,//error en la compra
            'club_to_id' => $request->club_to_id,
            'jugador_id' => $request->jugador_id,
            'created_contract' => $currentDateTime,
            'contract_min' => $request->club_to_id == null ? $currentDateTime : $newDateTime
        ]);

        //comprovar equip que esta el jugador.
        $jugador = Jugador::find($request->jugador_id);
        $jugador->update(['club_id' => $request->club_to_id]);

        return response()->json([
            'jugador' => Jugador::find($request->jugador_id),
            'request' => $request->all(),
            'msg' => 'operacio realitzada'
        ], 200);

    }

    //entrenador

    //es consulta a la db taula transferencies_entrenadors i s'obtenen tots els resultats.
    public function allTransferenciesEntrenadorall(){

        $entrenadors = DB::table('transferencies_entrenadors')->get();

        return response()->json([
            'entrenadors' => $entrenadors
        ], 200);

    }

    //consulta a la db les transferencies fetes per un entrenador.
    public function allTransferenciesEntrenador(Entrenador $entrenador){

        $entrenador_transferencies = DB::table('transferencies_entrenadors')->where('entrenador_id', $entrenador->id)->get();

        return response()->json([
            'entrenador' => $entrenador_transferencies
        ], 200);

    }

    //consulta a la db les transferencies fetes per un club dels entrenadors.
    public function allTransferenciesEntrenadorclub(Club $club){

        $entrenador_transferencies = TransferenciesEntrenador::where('club_from_id', $club->id)
                                            ->orWhere('club_to_id', $club->id)->paginate(5);
                                    
        for($i = 0; $i < sizeof($entrenador_transferencies); $i++)
        {
            if($entrenador_transferencies[$i]->club_from_id !== null)
            {
                $entrenador_transferencies[$i]->club_from_id = $entrenador_transferencies[$i]->venedor;
            }

            if($entrenador_transferencies[$i]->club_to_id !== null)
            {
                $entrenador_transferencies[$i]->club_to_id = $entrenador_transferencies[$i]->comprador;
            }

            $entrenador_transferencies[$i]->entrenador_id = $entrenador_transferencies[$i]->entrenador;

        }

        return response()->json([
            'entrenador' => $entrenador_transferencies
        ], 200);

    }

    //funcio per comprar un entrenador nou
    public function compraEntrenador(TransferenciaEntrenadorRequest $request){

        try{
            
            //validadio de l'informacio arribada per el request.
            $request->validated($request->all());

            //es comprova que l'usuari actiu pertany al club que vol der l'operacio de compra.
            if(Auth::user()->club_id != $request->club_to_id)
            {
                return response()->json([
                    'msg' => "Aquest usuari no pot fer aquesta accio."
                ], 404);
            }

            //es busca a la db l'entrenador per id.
            $entrenador_fixatge = Entrenador::find($request->entrenador_id);   

            //comprovar l'equip que esta l'entrenador.
            //si es igual salta error ja que si esta al mateix equip no hi ha traspas.
            if($entrenador_fixatge->club_id == $request->club_to_id)
            {
                //s'envia un error per veure que aquet entrenador ja pertany aquet club.
                return response()->json([
                    'msg' => "L'entrenador ja esta al teu equip"
                ], 404);
                
            }
            else
            {
                //en el cas de no estar al mateix equip. S'afegeix a la request la variable entrenador_club_id amb el valor corresponent.
                $request->request->add(['entrenador_club_id' => $request->club_to_id]);
            }

            //es mira l'ultima transferencia feta per l'entrenador i es comprova apartir d'aqui amb la data de contracte.
            $transferencia = TransferenciesEntrenador::select()
                                    ->where('entrenador_id', $request->entrenador_id)->orderBy('id', 'desc')->first();

            //es per veure el dia actual en que ens trobem.
            $currentDateTime = Carbon::today()->toDateString();

            //primer es mira que la transferencia rebuda no sigui null ja que si es null vol dir que es un jugador que no ha tingut mai cap equip.
            if($transferencia !=  null)
            {
            //si aquesta data es superior a l'actual no es podra fixar l'entrenador.
                if($transferencia->contract_min > $currentDateTime)
                {
                    //s'envia un error per veure que aquesta operacio no es pot fer perque esta en contracte o periode de gracia amb l'equip que ha firmat.
                    return response()->json([
                                'contract' => $transferencia->contract_min,
                                'dateTime' => $currentDateTime,
                                'msg' => "Periode que no es pot fitxar l'entrenador"
                            ], 404);
                }
            }

            //aixo no es fara es cridara primer la venta del entrenador i despres la compra del nou
            //$entrenador_club_actual = Entrenador::where('club_id', $request->club_to_id)->first();
            //$entrenador_club_actual->club_id = null;
            //$this->changeEntrenadorClub($entrenador_club_actual);

            //aqui comprarias l'entrenador.
            return $this->createTransferenciesEntrenador($request);

        }
        catch(Exception $e){
            //en cas de que sorgis algun error pasa per el catch.
            return response()->json($e, 400);
        }
        
    }

    //funcio per vendre l'entrenador que tens en plantilla.
    public function ventaEntrenador(TransferenciaEntrenadorRequest $request){

        try{

            //validacio de les dades del $request.
            $request->validated($request->all());

            //es comprova que l'usuari es el mateix del club que vol fer l'operacio de venta.
            if(Auth::user()->club_id != $request->club_from_id)
            {
                return response()->json([
                    'request' => $request->all(),
                    'auth' => Auth::user()->club_id,
                    'club_from_id' => $request->club_from_id,
                    'msg' => "Aquest usuari no pot fer aquesta accio."
                ], 404);
            }

            //es mira quin equip es troba l'entrenador 
            $entrenador = Entrenador::select()
                                    ->where('id', $request->entrenador_id)->first();
            
            //es comprova que no sigui null ja que si es null es un agent lliure no te equip
            if($entrenador !=  null)
            {
            //si aquesta data es diferent a l'entrenador registrat salta el missatge.
                if($entrenador->club_id != $request->club_from_id)//$entrenador->id??
                {
                    return response()->json([
                                'msg' => "Aquet entrenador no es el teu, per tant no el pots vendre."
                            ], 404);
                }
            }
            
            return $this->createTransferenciesEntrenador($request);

        }
        catch(Exception $e){
            //en cas de que sorgis algun error pasa per el catch.
            return response()->json($e, 400);
        }
        
    }

    //crear la transferencia en la taula pertinent i apart actualitzas club_id d'entrenador taula
    private function createTransferenciesEntrenador(Request $request){

        //comprovacio transferencia
        $currentDateTime = Carbon::today()->toDateString();
        $newDateTime = Carbon::today()->addDays(5)->toDateString();

        TransferenciesEntrenador::create([
            'club_from_id' => $request->club_from_id,//error en la compra
            'club_to_id' => $request->club_to_id,
            'entrenador_id' => $request->entrenador_id,
            'created_contract' => $currentDateTime,
            'contract_min' => $request->club_to_id == null ? $currentDateTime : $newDateTime
        ]);

        //comprovar equip que esta el jugador.
        $entrenador = Entrenador::find($request->entrenador_id);
        $entrenador->update(['club_id' => $request->club_to_id]);//retocar

        return response()->json([
            'entrenador' => Entrenador::find($request->entrenador_id),
            'request' => $request->all()
        ], 200);

    }

    /*private function changeEntrenadorClub(Entrenador $entrenador)
    {
        $entrenador->update();
        return response()->json([
            'entrenador' => $entrenador,
        ], 200);
    }*/

}
