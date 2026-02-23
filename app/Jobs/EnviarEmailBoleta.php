<?php
namespace App\Jobs;

use App\Models\Boleta;
use App\Models\Cliente;
use App\Services\BrevoService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EnviarEmailBoleta implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $backoff = 60;

    public function __construct(
        public readonly Cliente $cliente,
        public readonly Boleta  $boleta,
    ) {}

    public function handle(BrevoService $brevo): void
    {
        $brevo->enviar(
            destinatario: $this->cliente->email,
            asunto:       '¡Recibimos tu comprobante!',
            cuerpo:       view('emails.portal.boleta-recibida', [
                'cliente' => $this->cliente,
                'boleta'  => $this->boleta,
            ])->render(),
            tipo:     'boleta_recibida',
            userId:   $this->cliente->id,
        );
    }
}