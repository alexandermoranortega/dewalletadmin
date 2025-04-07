<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Images;
use Input;
use DB;

class ImagenApiController extends Controller
{
    
    public function cargaimagen(Request $request)
    {
        $today = date("Y-m-d H:i:s");  
        $id = $request->vendedor.'_'.$today.'.png';

    
        if($archivo=$request->file('file')){
            $path= asset('images/facturas/'.$id);
            $archivo->move('images/facturas', $id);
        }

        try {
            DB::beginTransaction();
        
                Images::insert([ 
                    'codigo_vendedor'=>$request->vendedor,
                    'url_imagen'=>$path,
                    'updated_at' =>now(),
                    'created_at' =>now(),
		    'codigo_identificador'=>$request->codigoIdentificador,     
		]);
            
            DB::commit();

            return response()->json(["sms"=>true,"mensaje"=>"Imagen guardada correctamente"]);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(["sms"=>false,"mensaje"=>$e->getMessage()]);                 
        }
       
    }
}
