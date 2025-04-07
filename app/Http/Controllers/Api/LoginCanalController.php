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


class LoginCanalController extends Controller
{
    public function credencialLocal(Request $request)
    {      
    // Primero buscamos en la tabla Vendedor
    $result = Vendedor::where('email', $request->email)
        ->whereNull('deleted_at')
        ->first();

    if (is_null($result)) {
        // Si no se encuentra en Vendedor, buscamos en la tabla Local
        $result = Local::where('email', $request->email)
            ->whereNull('deleted_at')
            ->first();

        if (is_null($result)) {
            // Si tampoco se encuentra en Local, retornamos credenciales inválidas
            return response()->json(["sms" => false, "mensaje" => "Credenciales invalidas"]);
        }

        // Si se encuentra en Local, verificamos la contraseña
        if (password_verify($request->password, $result->password)) {
            return response()->json([
                "sms" => true,
                "mensaje" => "Ingreso Correcto",
                "local" => $result->id,
                "codigo_c" => $result->ruc,
                "codigo_v" => '', // No hay código de vendedor en Local
                "lista_precio" => $result->ListaPrecio,
                "dfa" => $result->dfa,
                "rol" => "canal"
            ]);
        } else {
            return response()->json(["sms" => false, "mensaje" => "Credenciales invalidas"]);
        }
    }

    // Si se encuentra en Vendedor, verificamos la contraseña
    if (password_verify($request->password, $result->password)) {
        // Buscamos la información del local asociado al vendedor
        $localres = Local::where('id', $result->id_local)
            ->whereNull('deleted_at')
            ->first();

        return response()->json([
            "sms" => true,
            "mensaje" => "Ingreso Correcto",
            "local" => $result->id_local,
            "codigo_c" => $localres->ruc,
            "codigo_v" => $result->ci_ruc,
            "lista_precio" => $localres->ListaPrecio,
            "dfa" => $result->dfa,
            "rol" => "vendedor"
        ]);
    } else {
        return response()->json(["sms" => false, "mensaje" => "Credenciales invalidas"]);
    }
    }
}



  