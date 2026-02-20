<?php

namespace App\Services;

use App\Models\Boleta;
use App\Models\Punto;

class PuntosService
{
    public function acreditar(Boleta $boleta, int $puntos): Punto
    {
        return Punto::create([
            'cliente_id'  => $boleta->cliente_id,
            'boleta_id'   => $boleta->id,
            'puntos'      => $puntos,
            'descripcion' => "Puntos acreditados por boleta #{$boleta->numero_boleta}",
        ]);
    }

    public function totalCliente(string $clienteId): int
    {
        return Punto::where('cliente_id', $clienteId)->sum('puntos');
    }
}