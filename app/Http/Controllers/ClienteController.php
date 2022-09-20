<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Repositories\ClienteRepository;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    
        public function __construct(Cliente $cliente){
            $this->cliente = $cliente; 
        }


        //MÉTODO RESPONSAVEL POR LISTAR TODOS OS DADOS    
        public function index(Request $request)
        {
            $clienteRepository = new ClienteRepository($this->cliente);

            if($request->has('filtro')){
                $clienteRepository->filtro($request->filtro); 
            }
    
            if($request->has('atributos')){ 
                $clienteRepository->selectAtributos($request->atributos);
            }
    
            return response()->json($clienteRepository->getResultado(),200);
        }

        public function store(Request $request){

            $request->validate($this->cliente->rules(),$this->cliente->feedback());
    
            $cliente = $this->cliente->create([
                'nome'    => $request->nome
            ]);
    
            return response()->json($cliente,201);
        }


        public function show($id){

            $cliente = $this->cliente->find($id);
    
            if($cliente === null){
                return response()->json(['erro' => 'Recurso pesquisado não existe'],404);
            }
     
            return response()->json($cliente,200);  
        } 


        public function update(Request $request, $id){ 

            $cliente = $this->cliente->find($id);
    
            if($cliente === null){
                return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe!!'],404) ;
            }
    
            if($request->method()==='PATCH'){
    
                $regrasDinamicas = array();
                
                //Percorrendo todas as regras definidas no Model Marca
                foreach ($cliente->rules() as $input => $regra) {
                    
                    //Coletar apenas as trhtas aplicáveis aos parâmetros parciais da requisição
                    if(array_key_exists($input,$request->all())){
                        $regrasDinamicas[$input] = $regra;
                    }
                    
                }
    
                $request->validate($regrasDinamicas,$cliente->feedback());
     
            }else{
                $request->validate($cliente->rules(),$cliente->feedback());
            }
    
            $cliente->fill($request->all());
            $cliente->save();
    
            return response()->json($cliente,200);
        }


        public function destroy($id){
        
            $cliente = $this->cliente->find($id);
    
            if($cliente === null){
                return response()->json(['erro' => 'Impossível realizar a exclussão. O recurso solicitado não existe!!'],404);
            }

    
            $cliente->delete();
            return response(['msg' => 'O cliente foi removido com sucesso!'],200);
        }



    




}
