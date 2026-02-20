<?php

namespace App\Jobs;

use App\Models\Cliente;
use App\Services\BrevoService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EnviarEmailRegistro implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        public readonly Cliente $cliente,
    ) {}

    public function handle(BrevoService $brevo): void
    {
        $brevo->enviar(
            destinatario: $this->cliente->email,
            asunto: '¡Registro exitoso! Bienvenido',
            cuerpo: view('emails.registro', ['cliente' => $this->cliente])->render(),
            tipo: 'registro_cliente',
            clienteId: $this->cliente->id,
        );
    }
}