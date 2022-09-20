<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    protected $fillable = ['nome','imagem'];


    public function rules(){
        return [
            'nome'   => 'required|unique:marcas,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png,jpg,jpeg' 
        ];
    }

    public function feedback(){
       return [
            'required'     => 'O campo :attribute é obrigatório',
            'nome.unique'  => 'O nome da marca já existe',
            'nome.min'     => 'O nome deve ter no mínimo 3 caracteres',
            'imagem.mimes' => 'O arquivo deve ser uma imagem do tipo png,jpg ou jpeg'
        ];
    }


    public function modelos(){
        //UMA marca tem MUITOS modelos
        return $this->hasMany('App\Models\Modelo');
    }




}//end class