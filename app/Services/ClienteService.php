<?php

namespace App\Services;

use App\Models\Cliente;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ClienteService
{
    public function __construct(
        private readonly BrevoService $brevoService,
    ) {}

    public function listar(array $filtros = []): LengthAwarePaginator
    {
        return Cliente::query()
            ->when(isset($filtros['tipo_persona']), fn($q) => $q->where('tipo_persona', $filtros['tipo_persona']))
            ->when(isset($filtros['estado']), fn($q) => $q->where('estado', $filtros['estado']))
            ->when(isset($filtros['search']), fn($q) => $q->where(function ($q) use ($filtros) {
                $q->where('nombre', 'ilike', "%{$filtros['search']}%")
                  ->orWhere('email', 'ilike', "%{$filtros['search']}%")
                  ->orWhere('dni', 'ilike', "%{$filtros['search']}%")
                  ->orWhere('ruc', 'ilike', "%{$filtros['search']}%");
            }))
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function registrar(array $data, ?UploadedFile $archivo = null): Cliente
    {
        return DB::transaction(function () use ($data, $archivo) {
            if ($archivo) {
                $data['archivo_comprobante'] = $archivo->store('comprobantes', 'private');
            }

            $cliente = Cliente::create($data);

            dispatch(new \App\Jobs\EnviarEmailRegistro($cliente));

            return $cliente;
        });
    }

    public function activar(Cliente $cliente): Cliente
    {
        $cliente->update(['estado' => 'activo']);
        return $cliente;
    }

    public function rechazar(Cliente $cliente): Cliente
    {
        $cliente->update(['estado' => 'rechazado']);
        return $cliente;
    }
}