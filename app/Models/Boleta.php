<?php

namespace App\Models;

use App\Concerns\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Boleta extends Model implements AuditableContract
{
    use Auditable;
    use HasAuditFields;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'cliente_id',
        'archivo',
        'numero_boleta',
        'monto',
        'puntos_otorgados',
        'estado',
        'observacion',
    ];

    protected function casts(): array
    {
        return [
            'monto'            => 'decimal:2',
            'puntos_otorgados' => 'integer',
        ];
    }

    public function scopePendiente($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeAceptada($query)
    {
        return $query->where('estado', 'aceptada');
    }

    public function scopeRechazada($query)
    {
        return $query->where('estado', 'rechazada');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function punto()
    {
        return $this->hasOne(Punto::class);
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class);
    }
}
