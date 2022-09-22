<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locacao extends Model
{
    use HasFactory;

    protected $fillable = [
                            'cliente_id',
                            'carro_id',
                            'data_inicio_periodo',
                            'data_final_previsto_periodo',
                            'data_final_realizado_periodo',
                            'valor_diaria',
                            'km_inicial',
                            'km_final' ];


    protected $table = 'locacoes';

    

    public function rules(){
                        return [ 
                                'cliente_id'                   => 'required|integer',
                                'carro_id'                     => 'required|integer',
                                'data_inicio_periodo'          => 'required',
                                'data_final_previsto_periodo'  => 'required',
                                'data_final_realizado_periodo' => 'required',
                                'valor_diaria'                 => 'required',
                                'km_inicial'                   => 'required',
                                'km_final'                     => 'required' 
                            ];
    }

    public function feedback(){
                return [
                        'cliente_id.required'                  => 'O campo :attribute deve ser preenchido',
                        'carro_id.required'                    => 'O campo :attribute deve ser preenchido',
                        'data_inicio_periodo.required'         => 'O campo :attribute deve ser preenchido',
                        'data_final_previsto_periodo.required' => 'O campo :attribute deve ser preenchido',
                        'valor_diaria.required'                => 'O campo :attribute deve ser preenchido',
                        'km_inicial.required'                  => 'O campo :attribute deve ser preenchido',
                        'km_final.required'                    => 'O campo :attribute deve ser preenchido',
                    ];
    }






}
