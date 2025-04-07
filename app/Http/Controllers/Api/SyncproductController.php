<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Producto;
use DB;
use Illuminate\Support\Facades\Http;

class SyncproductController extends Controller
{
    
    public function rel_categ(Request $request)
    {

        $consulta=Producto::select('cod_producto')->whereNull('deleted_at')->get();

        try {
            DB::beginTransaction();
        
        foreach($consulta as $obt){
            
            $this->actualizacioncateg($obt->cod_producto);
            $this->addurl($obt->cod_producto);
           

        }
        DB::commit();

            return response()->json(["sms"=>true,"mensaje"=>"Se proceso correctamente"]);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(["sms"=>false,"mensaje"=>$e->getMessage()]);                 
        }

       
    }


    public function actualizacioncateg($codigo_producto){


        $info = Http::get('http://201.218.2.21:8055/api/movil/relaciondatosproductoscategorias',[
            'codigoproducto' => $codigo_producto,
        ]);

        $update=json_decode($info->body());

        if(isset($update->data[0])){
            $update2=($update->data[0]);

            Producto::where('cod_producto', $codigo_producto)->update([
                'marca' => $update2->Marca,
                'nivel1'=> $update2->Nivel1,
                'NombreNivel1'=> $update2->NombreNivel1,
                'nivel2'=> $update2->Nivel2,
                'NombreNivel2'=> $update2->NombreNivel2,
                'nivel3'=> $update2->Nivel3,
                'NombreNivel3'=> $update2->NombreNivel3
            ]);
           
        }
        


    }

    public function addurl($codigo_producto){
        $info = Http::get('http://201.218.2.21:8055/api/movil/relaciondatosproductosurl',[
            'codigoproducto' => $codigo_producto,
        ]);

        $update=json_decode($info->body());

        if(isset($update->data[0])){
            $update2=($update->data[0]);

            Producto::where('cod_producto', $codigo_producto)->update([
                'url_image' => $update2->url_image
            ]);

        }

    }
}

