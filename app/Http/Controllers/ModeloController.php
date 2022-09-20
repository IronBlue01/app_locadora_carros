<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use App\Repositories\ModeloRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{

    public function __construct(Modelo $modelo){
        $this->modelo = $modelo; 
    }
   
    public function index(Request $request){


        $modeloRepository = new ModeloRepository($this->modelo);

        if($request->has('atributos_marca')){
            $atributos_marca =  'marca:id,'.$request->atributos_marca;
            $modeloRepository->selectAtributosRegistrosRelacionados($atributos_marca);
        }else{
            $modeloRepository->selectAtributosRegistrosRelacionados('marca');
        }


        if($request->has('filtro')){
            $modeloRepository->filtro($request->filtro); 
        }

        if($request->has('atributos')){ 
            $modeloRepository->selectAtributos($request->atributos);
        }

        return response()->json($modeloRepository->getResultado(),200);
    }
  
    public function store(Request $request){

        $request->validate($this->modelo->rules());

        $imagem = $request->file('imagem'); 
        $imagem_urn = $imagem->store('imagens/modelos','public'); 

        $modelo = $this->modelo->create([
            'marca_id'      => $request->marca_id, 
            'nome'          => $request->nome,
            'imagem'        => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares'       => $request->lugares,
            'air_bag'       => $request->air_bag,
            'abs'           => $request->abs            
        ]);

        return response()->json($modelo,201);
    }
    

    
    public function show($id){

        $modelo = $this->modelo->with('marca')->find($id);

        if($modelo===null){
            return response()->json(['erro' => 'Recurso pesquisado não existe'],404);
        }
 
        return response()->json($modelo,200);  
    }


   
    public function update(Request $request, $id){

        $modelo = $this->modelo->find($id);

        if($modelo === null){
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe!!'],404) ;
        }

        if($request->method()==='PATCH'){

            $regrasDinamicas = array();
            
            //Percorrendo todas as regras definidas no Model Modelo
            foreach ($modelo->rules() as $input => $regra) {
                
                //Coletar apenas as trhtas aplicáveis aos parâmetros parciais da requisição
                if(array_key_exists($input,$request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
                
            }

            $request->validate($regrasDinamicas);
 
        }else{
            $request->validate($modelo->rules());
        }

        if($request->file('imagem')){
            Storage::disk('public')->delete($modelo->imagem);
        }

        
        $imagem = $request->file('imagem'); 

        //Ao passar o $request->all() para o method fill() será sobreposto no objeto modelo
        //todo os campos que vir do request 
        //como ainda irá existir id no objeto ao dar um save o laravel irá apenas atualizar as informações 
        
        $modelo->fill($request->all());

        if($imagem){
            $imagem_urn = $imagem->store('imagens/modelos','public');
            $modelo->imagem = $imagem_urn; 
        }
        $modelo->save();


        // $modelo->update([
        //     'marca_id'      => $request->marca_id, 
        //     'nome'          => $request->nome,
        //     'imagem'        => $imagem_urn,
        //     'numero_portas' => $request->numero_portas,
        //     'lugares'       => $request->lugares,
        //     'air_bag'       => $request->air_bag,
        //     'abs'           => $request->abs 
        // ]);

        return response()->json($modelo,200);
    }

  
    public function destroy($id){
        
        $modelo = $this->modelo->find($id);

        if($modelo === null){
            return response()->json(['erro' => 'Impossível realizar a exclussão. O recurso solicitado não existe!!'],404);
        }

        //Remove o arquivo antigo
        Storage::disk('public')->delete($modelo->imagem);

        $modelo->delete();
        return response(['msg' => 'O Modelo foi removida com sucesso!'],200);
    }
}
