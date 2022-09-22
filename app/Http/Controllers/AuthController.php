<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request){
        
        $credenciais = $request->all(['email','password']);

        //autenticação email e senha
        $token = auth('api')->attempt($credenciais);
        

        if($token){ //Usuário autenticado com sucesso
                return response()->json(['token' => $token],200);
        } else {  //erro de usuário ou senha    
                return response()->json(['erro' => 'Usuário ou senha inválido'],403);

                //401 = Unauthorized -> não autorizado
                //403 = forbiden -> proibido (login inválido)

        }



        //Retornar um token JWT
        return 'login';
    }

    public function logout(){
        return 'logout';
    }

    public function refresh(){
        return 'refresh';
    }

    public function me(){
        return response()->json(auth()->user());
    }


}
