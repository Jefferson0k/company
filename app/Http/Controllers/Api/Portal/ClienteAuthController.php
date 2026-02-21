<?php

namespace App\Http\Controllers\Api\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\Registro\StoreClienteRequest;
use App\Jobs\EnviarEmailRegistro;
use App\Models\Cliente;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ClienteAuthController extends Controller
{
    public function register(StoreClienteRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            // 1. Crear cliente sin comprobante aún
            $cliente = Cliente::create([
                'tipo_persona'     => $request->tipo_persona,
                'nombre'           => $request->nombre,
                'apellidos'        => $request->apellidos,
                'dni'              => $request->dni,
                'ruc'              => $request->ruc,
                'departamento'     => $request->departamento,
                'email'            => $request->email,
                'password'         => $request->password, // cast 'hashed' lo encripta
                'telefono'         => $request->telefono,
                'acepta_politicas' => true,
                'acepta_terminos'  => true,
                'estado'           => 'pendiente',
            ]);

            // 2. Subir archivo a carpeta del cliente en MinIO
            $rutaComprobante = Storage::disk('s3')->putFile(
                "clientes/{$cliente->id}/comprobantes",
                $request->file('archivo_comprobante')
            );

            // 3. Actualizar cliente con la ruta del comprobante
            $cliente->update(['archivo_comprobante' => $rutaComprobante]);

            // 4. Enviar email de bienvenida en cola
            EnviarEmailRegistro::dispatch($cliente)->onQueue('emails');

            DB::commit();

            return response()->json([
                'message' => 'Registro exitoso. En breve recibirás un correo de confirmación.',
                'cliente' => [
                    'id'           => $cliente->id,
                    'nombre'       => $cliente->nombre,
                    'email'        => $cliente->email,
                    'tipo_persona' => $cliente->tipo_persona,
                    'estado'       => $cliente->estado,
                ],
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            // Limpiar carpeta del cliente en MinIO si algo falló
            if (isset($cliente)) {
                Storage::disk('s3')->deleteDirectory("clientes/{$cliente->id}");
            }

            Log::error('Error en registro de cliente', [
                'error' => $e->getMessage(),
                'email' => $request->email,
                'ip'    => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Error al registrar. Intenta nuevamente.',
            ], 500);
        }
    }
}