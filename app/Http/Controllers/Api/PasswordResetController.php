<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\Vendedor;
use App\Models\PasswordResetCode;
use App\Mail\PasswordResetCodeMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\PasswordResetMail;

class PasswordResetController extends Controller
{
    /**
     * Envía código de restablecimiento de contraseña
     */
public function sendResetCode(Request $request)
{
    $request->validate([
        'email' => 'required|email|max:255',
    ]);

    $email = $request->email;

    if (!Vendedor::where('email', $email)->exists()) {
        return response()->json([
            'success' => false,
            'error' => 'EMAIL_NOT_FOUND',
            'message' => 'El correo electronico no esta registrado',
        ], 404);
    }

    $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $expiresAt = now()->addMinutes(15);

    try {
        PasswordResetCode::updateOrCreate(
            ['email' => $email],
            ['code' => $code, 'expires_at' => $expiresAt]
        );

        Mail::to($email)->send(new PasswordResetMail($code));

        return response()->json([
            'success' => true,
            'message' => 'Codigo de verificacion enviado',
            'expires_at' => $expiresAt->toIso8601String(),
        ]);
    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'error' => 'SERVER_ERROR',
            'message' => 'Error al procesar la solicitud',
        ], 500);
    }
}

    /**
     * Valida el código y actualiza la contraseña
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'code' => 'required|string|size:6',
            'new_password' => 'required|string|min:8|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'VALIDATION_ERROR',
                'message' => $validator->errors()->first()
            ], 400, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        }

        $email = Str::lower(trim($request->email));
        $code = trim($request->code);

        try {
            $resetCode = PasswordResetCode::where('email', $email)
                ->where('code', $code)
                ->first();

            if (!$resetCode) {
                return response()->json([
                    'success' => false,
                    'error' => 'INVALID_CODE',
                    'message' => 'Código inválido o incorrecto'
                ], 400, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
            }

            if (Carbon::now()->gt($resetCode->expires_at)) {
                return response()->json([
                    'success' => false,
                    'error' => 'EXPIRED_CODE',
                    'message' => 'El código de verificación ha expirado'
                ], 400, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
            }

            DB::transaction(function () use ($email, $request, $resetCode) {
                $vendedor = Vendedor::where('email', $email)->firstOrFail();
                $vendedor->password = Hash::make($request->new_password);
                $vendedor->save();
                $resetCode->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada exitosamente'
            ], 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);

        } catch (\Exception $e) {
            Log::error('Error al resetear contraseña: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'SERVER_ERROR',
                'message' => 'Ocurrió un error al actualizar su contraseña'
            ], 500, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        }
    }
}