<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boletas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->string('archivo');
            $table->string('numero_boleta')->nullable();
            $table->decimal('monto', 10, 2)->nullable();
            $table->integer('puntos_otorgados')->default(0);
            $table->enum('estado', ['pendiente', 'aceptada', 'rechazada'])->default('pendiente');
            $table->text('observacion')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('estado');
            $table->index('cliente_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boletas');
    }
};
