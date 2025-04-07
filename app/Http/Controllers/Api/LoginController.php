<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Vendedor;
use App\Models\Local;
use Hash;
use Session;
use Validator;
use Auth;


class LoginController extends Controller
{
    public function credencial(Request $request)
    {      
      $result=Vendedor::where('email', $request->email)
        ->whereNull('deleted_at')
        ->first();

      if (is_null($result)) {
        return response()->json(["sms"=>false,"mensaje"=>"Credenciales invalidas"]);
      } else{ 
          if (password_verify($request->password,$result->password)) { 
            
            $localres=Local::where('id', $result->id_local)
            ->whereNull('deleted_at')
            ->first();

            return response()->json(["sms"=>true,"mensaje"=>"Ingreso Correcto","local"=>$result->id_local, "codigo_c"=>$localres->ruc, 
		"codigo_v"=>$result->ci_ruc,"lista_precio"=>$localres->ListaPrecio,"dfa"=>$result->dfa,"rol"=> "vendedor"]);
          }else{
            return response()->json(["sms"=>false,"mensaje"=>"Credenciales invalidas"]);
          }
      }
    }
}
