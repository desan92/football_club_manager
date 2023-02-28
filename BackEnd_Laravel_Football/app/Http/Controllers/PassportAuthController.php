<?php

namespace App\Http\Controllers;

//validacio del login 
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PassportAuthController extends Controller
{
    //
    /**
     * Registration
     */
    /*public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:4',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
 
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
       
        $token = $user->createToken('LaravelAuthApp')->accessToken;
 
        return response()->json(
            ['token' => $token],
             200
            );
    }*/
 
    /**
     * Login
     */

    public function renew(){

        if(Auth::user())
        {
            
            $user = Auth::user();
            $token = $user->createToken('Passport Oauth2')->accessToken;

            return response()->json(
                [
                    'ok' => 200,
                    'user' => $user,
                    'club' => $user->club,
                    'token' => $token
                ], 
                200
            );
        }
        else
        {
            return response()->json(
                [
                    'msg' => 'No estas autoritzat.'
                ], 
                401
            );
        }
        
    }

    public function login(LoginRequest $request)
    {
        //es valida la request.
        //es pasa $request->all() aixo recull tots els inputs del formulari passats i es valida amb el
        //validated, que compara l'entrada del formulari amb els requisits d'entrada del email i el password.
        $request->validated($request->all());

        //aixo seria igual a:
        /*$request->validated($credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ]);*/
        
        //El attempt mètode s'utilitza normalment per gestionar els intents d'autenticació des del 
        //formulari d'inici de sessió de la vostra aplicació. Si l'autenticació té èxit, hauríeu de 
        //regenerar la sessió de l'usuari per evitar la fixació de la sessió

        //$request->only(['email', 'password']) el que fa es agafar aquestes dades dels request. Cap mes.
        //en aquet cas com que nomes hi haura email i password es podria posar l'all() també pero si es passesin
        //mes camps donaria error. Comprovat al postman posant una altre variable.
        if (Auth::attempt($request->only(['email', 'password']))) {

            //es recull l'informacio del usuari de la bd.
            $user = User::where('email', $request->email)->first();

            //com que l'autentificacio es positiva entra dins l'if i genera el token nou.
            $token = $user->createToken('Passport Oauth2')->accessToken;

            //retorna una resposta positiva amb el token i l'usuari.
            return response()->json(
                [
                    'ok' => 200,
                    'token' => $token,
                    'user' => $user,
                    'club' => $user->club, //pots cridar aixi
                    //'club1' => $user->club1() //tambe pots cridar al metode.
                ], 
                200
            );
        } 
        else 
        {
            //retorna un error.
            return response()->json(
                [
                    'error' => 'Unauthorised',
                ],
                 401
            );
        }
    }  
    
    public function update(UpdateUserRequest $request, $id)
    {
        try{

            //validem les dades entrades desde el formulari.
            $request->validated();

            //buscar usuari per veure si existeix.
            $user = User::where('id', $id)->first();

            //si no existeix es llença un error.
            if(!$user)
            {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found!'
                ], 400);
            }

            //formatejar l'entrada de dades. En el cas de la password li poses el hash
            //Amb user::where id es busca aquest usuari i es fa l'update amb les dades pasades.
            $updated = User::where('id', $id)->update([
                'nom' => $request->nom,
                'cognom' => $request->cognom,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            //si l'update s'ha realitzat s'envia una resposta positiva i sino doncs una resposta negativa.
            if($updated)
            {
                //buscar usuari per obtenir les dades actualitzades.
                $userUpdated = User::where('id', $id)->first();

                //return en cas de que sigui positiva l'actualitzacio.
                return response()->json(
                    [
                        'succes' => true,
                        'user' => $userUpdated,
                        'request' => $request->all()
                    ], 
                    200
                );
            }
            else
            {
                return response()->json([
                    'success' => false,
                    'message' => 'User can not be updated'
                ], 500);
            }
            
        }
        catch(Exception $e){
            //en cas d'haber surgit algun error en el proces pasaria per el catch i rebriem la resposta.
            return response()->json([
                'success' => false,
                'message' => $e
            ], 500);
        }
        
    }

    public function logout(Request $request)
    {
        //aqui el que fas es anular el token generat amb anterioritat. El revoques perque no es
        //pugui fer servir. S'agafa l'informacio que et retorna de la taula oauth_access_tokens
        //i el camp revoked es pasa a true.
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out',
            //aixo es per veure que retorna ($request->user()) retorna tota l'informacio del usuari.
            'user' => $request->user(),
            //($request->user()->token()), retorna tota l'informacio relacionada amb el token que es pasa.
            //seria l'informacio que es trobaria a la taula oauth_access_tokens
            'token' => $request->user()->token()
        ]);
    }

}
