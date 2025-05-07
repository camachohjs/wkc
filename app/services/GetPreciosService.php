<?php

namespace App\services;

use App\Models\Categoria;
use App\Models\TorneosPrecios;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetPreciosService
{
    public function precioCategoria($categorias, $id_torneo)
    {
        $categorias = collect($categorias);
        //obtener categorias ordenadas por fecha
        $categoriasOrdenadas = $categorias->sortBy(function ($categoria) {
            return $categoria['fecha'];
        });
        //obtener fechas distintantas de las categorias
        $fechasDistintas = $categorias->pluck('fecha')->unique()->sort()->values();

        //variables:
        $kata = 0;
        $combate = 0;
        $especial = 0;
        $cuarteta = 0;
        $equipo = 0;

        $boolCombo = false;


        $precioBasica = 0;     //id 1
        $precioCatAdicional = 0;
        $precioCatEspecialOCuarteta = 0; //id 3
        $precioCatEspecialYCuarteta = 0;
        $precioCatEspecialMas1Cat = 0;
        $precioCatEspecialMas2Cat = 0;
        $precioCatEquipoCombate = 0;  //id 7
        $precioPaseCoach = 0;
        $paseEspactador = 0;

        $torneoPrecios = TorneosPrecios::where('torneo_id', $id_torneo)->get();
        
        $fechaActualCat = $fechasDistintas[0];
        $fechaActualCat = Carbon::createFromFormat('d/m/Y', $fechaActualCat);
        
        //obtener precios del torneo
        foreach ($torneoPrecios as $torneoPrecio) {
            if ($torneoPrecio->tipos_formas_id == 1) {
                $precioBasica = $fechaActualCat->isBefore($torneoPrecio->fecha) ? $torneoPrecio->costo_pre_registro : $torneoPrecio->costo_registro;            
            } else if ($torneoPrecio->tipos_formas_id == 2){
                $precioCatAdicional = $fechaActualCat->isBefore($torneoPrecio->fecha) ? $torneoPrecio->costo_pre_registro : $torneoPrecio->costo_registro;
            } else if ($torneoPrecio->tipos_formas_id == 3){
                $precioCatEspecialOCuarteta = $fechaActualCat->isBefore($torneoPrecio->fecha) ? $torneoPrecio->costo_pre_registro : $torneoPrecio->costo_registro;
            } else if ($torneoPrecio->tipos_formas_id == 4){
                $precioCatEspecialYCuarteta = $fechaActualCat->isBefore($torneoPrecio->fecha) ? $torneoPrecio->costo_pre_registro : $torneoPrecio->costo_registro;
            } else if ($torneoPrecio->tipos_formas_id == 5){
                $precioCatEspecialMas1Cat = $fechaActualCat->isBefore($torneoPrecio->fecha) ? $torneoPrecio->costo_pre_registro : $torneoPrecio->costo_registro;
            } else if ($torneoPrecio->tipos_formas_id == 6){
                $precioCatEspecialMas2Cat = $fechaActualCat->isBefore($torneoPrecio->fecha) ? $torneoPrecio->costo_pre_registro : $torneoPrecio->costo_registro;
            } else if ($torneoPrecio->tipos_formas_id == 7){
                $precioCatEquipoCombate = $fechaActualCat->isBefore($torneoPrecio->fecha) ? $torneoPrecio->costo_pre_registro : $torneoPrecio->costo_registro;
            } else if ($torneoPrecio->tipos_formas_id == 8){
                $precioPaseCoach = $fechaActualCat->isBefore($torneoPrecio->fecha) ? $torneoPrecio->costo_pre_registro : $torneoPrecio->costo_registro;
            } else if ($torneoPrecio->tipos_formas_id == 9){
                $paseEspactador = $fechaActualCat->isBefore($torneoPrecio->fecha) ? $torneoPrecio->costo_pre_registro : $torneoPrecio->costo_registro;
            }
        }
        //obtiene la cantidad de categorias de cada tipo: kata, combate, especial, cuarteta y equipo
        foreach ($categoriasOrdenadas as $categoria) {
            $cate = Categoria::find($categoria['id_categoria']);
            $tipoFormaId = $cate->forma->tipos_formas_id;
            $nombreMinuscula = strtolower($cate->forma->nombre);
        
            if ($tipoFormaId == 1) {
                if (strpos($nombreMinuscula, 'kata') !== false) {
                    $kata += 1;
                } else {
                    $combate += 1;
                }
            } else if ($tipoFormaId == 3) {
                if (strpos($nombreMinuscula, 'especial') !== false) {
                    $especial += 1;
                } else {
                    $cuarteta += 1;
                }
            } else if ($tipoFormaId == 7) {
                $equipo += 1;
            }
        }

        // log::info($categoriasOrdenadas);

        return $categoriasOrdenadas->map(function ($categoria) use (&$boolCombo, &$kata, &$combate, &$especial, &$cuarteta, &$equipo, &$precioBasica, &$precioCatAdicional, &$precioCatEspecialOCuarteta, &$precioCatEspecialYCuarteta, &$precioCatEspecialMas1Cat, &$precioCatEspecialMas2Cat, &$precioCatEquipoCombate, &$precioPaseCoach, &$paseEspactador) {
            // log::info(['kata' => $kata, 'combate' => $combate, $boolCombo]);
            // log::info($categoria['id_categoria']);
            // log::info('-------------------');
            
            $cate = Categoria::find($categoria['id_categoria']);
            $tipoFormaId = $cate->forma->tipos_formas_id;        
            if ($tipoFormaId == 3) {
                if($especial > 0 && $cuarteta > 0){
                    $categoria['precio'] = $precioCatEspecialYCuarteta;
                    $especial -= 1;
                    $cuarteta -= 1;
                }else if($especial > 0 && (($kata + $combate) > 1)){
                    if($kata > 1){
                        $categoria['precio'] = $precioCatEspecialMas2Cat;
                        $especial -= 1;
                        $kata -= 2;
                    }else if ($combate > 1){
                        $categoria['precio'] = $precioCatEspecialMas2Cat;
                        $especial -= 1;
                        $combate -= 2;
                    }else{
                        $categoria['precio'] = $precioCatEspecialMas1Cat;
                        $especial -= 1;
                        $kata -= 1;
                        $combate -= 1;
                    }
                }else if($especial > 0 && (($kata + $combate) > 0)){
                    if($kata > 0){
                        $categoria['precio'] = $precioCatEspecialMas1Cat;
                        $especial -= 1;
                        $kata -= 1;
                    }else if ($combate > 0){
                        $categoria['precio'] = $precioCatEspecialMas1Cat;
                        $especial -= 1;
                        $combate -= 1;
                    }
                }
                else if($especial > 0){
                    $categoria['precio'] = $precioCatEspecialOCuarteta;
                    $especial -= 1;
                }else if($cuarteta > 0){
                    $categoria['precio'] = $precioCatEspecialOCuarteta;
                    $cuarteta -= 1;
                }
            } else if ($tipoFormaId == 1) {
                if($combate > 0 && $kata > 0 && !$boolCombo){
                    $boolCombo = true;
                    $categoria['precio'] = $precioBasica;
                    $combate -= 1;
                    $kata -= 1;
                }else if($kata > 0){
                    if($boolCombo){
                        $categoria['precio'] = $precioCatAdicional;
                        $kata -= 1;
                    }else{
                        $categoria['precio'] = $precioBasica;
                        $kata -= 1;
                    }
                }else if($combate > 0){
                    if($boolCombo){
                        $categoria['precio'] = $precioCatAdicional;
                        $combate -= 1;
                    }else{
                        $categoria['precio'] = $precioBasica;
                        $combate -= 1;
                    }
                }
            } else if ($tipoFormaId == 7) {
                $categoria['precio'] = $precioCatEquipoCombate;
            }

            if($categoria['precio'] == '-'){
                $categoria['precio'] = 0;
            }
            return $categoria;
        });
    }

}
