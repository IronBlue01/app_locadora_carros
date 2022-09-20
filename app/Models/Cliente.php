<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];
    protected $table    = 'clientes'; 


    public function rules(){
            return [ 'nome' => 'required'];
    }

    public function feedback(){
        return ['nome.required' => 'O campo nome deve ser preenchido'];
    }





}
