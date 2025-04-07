<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Vendedor;
use Input;
use DB;

class ActOtpApiController extends Controller
{    
    public function actualizarOtp(Request $request)
    {
        try {
           DB::beginTransaction(); 
            $vendedor = Vendedor::where('ci_ruc', $request->identificacion)->first();

            if ($vendedor) {
                $vendedor->update(['dfa' => $request->valor]);
                DB::commit();
                return response()->json(["sms" => true, "mensaje" => "Dfa actualizado correctamente"]);
            } else {
                DB::rollBack();
                return response()->json(["sms" => false, "mensaje" => "La identificacion no fue encontrada"]);
            }        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(["sms"=>false,"mensaje"=>$e->getMessage()]);                 
        }
       
    }
}
