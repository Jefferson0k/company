<?php

namespace App\Services;

use App\Models\Boleta;
use App\Models\Cliente;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BoletaService
{
    public function __construct(
        private readonly PuntosService $puntosService,
        private readonly BrevoService $brevoService,
    ) {}

    public function listar(array $filtros = []): LengthAwarePaginator
    {
        return Boleta::query()
            ->with(['cliente:id,nombre,apellidos,email'])
            ->when(isset($filtros['estado']), fn($q) => $q->where('estado', $filtros['estado']))
            ->when(isset($filtros['cliente_id']), fn($q) => $q->where('cliente_id', $filtros['cliente_id']))
            ->when(isset($filtros['search']), fn($q) => $q->where('numero_boleta', 'ilike', "%{$filtros['search']}%"))
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function store(Cliente $cliente, array $data, UploadedFile $archivo): Boleta
    {
        return DB::transaction(function () use ($cliente, $data, $archivo) {
            $data['archivo']     = $archivo->store("boletas/{$cliente->id}", 'private');
            $data['cliente_id']  = $cliente->id;
            $data['estado']      = 'pendiente';

            return Boleta::create($data);
        });
    }

    public function aceptar(Boleta $boleta, int $puntos, ?string $observacion = null): Boleta
    {
        return DB::transaction(function () use ($boleta, $puntos, $observacion) {
            $boleta->update([
                'estado'           => 'aceptada',
                'puntos_otorgados' => $puntos,
                'observacion'      => $observacion,
            ]);

            $this->puntosService->acreditar($boleta, $puntos);

            dispatch(new \App\Jobs\EnviarEmailBoletaAceptada($boleta));

            return $boleta;
        });
    }

    public function rechazar(Boleta $boleta, string $observacion): Boleta
    {
        return DB::transaction(function () use ($boleta, $observacion) {
            $boleta->update([
                'estado'      => 'rechazada',
                'observacion' => $observacion,
            ]);

            dispatch(new \App\Jobs\EnviarEmailBoletaRechazada($boleta));

            return $boleta;
        });
    }
}