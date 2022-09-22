<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class UserController extends Controller
{

    public function __construct(User $user){   
      $this->user = $user;
    }

    public function store(Request $request){

        try {
            $user = $this->user;

            $request->validate($user->rules(),$user->feedback());

            $user->name     = $request->name; 
            $user->email    = $request->email; 
            $user->password = bcrypt($request->password); 
            $user->save();

            if($user){
                return response()->json(['msg' => 'Usuário cadastrado com sucesso!!'],201);
            } else {
                return response()->json(['erro' => 'Error ao cadastrar o usuário']);
            }

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => $th->getMessage()]);
        }



    }
}
