<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany};
use Illuminate\Database\Eloquent\Builder;

class Debt extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_PARTIAL = 'partial';
    const STATUS_PAID    = 'paid';

    protected $fillable = [
        'student_id',
        'concept',
        'amount',
        'status',
        'due_date'
    ];

    // Garantizamos que los montos y fechas se traten como objetos numéricos y de fecha respectivamente
    protected $casts = [
        'amount'   => 'decimal:2',
        'due_date' => 'date',
        'status'   => 'string'
    ];

    // Vinculación con el titular de la obligación financiera
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    // Relación con los pagos que han amortizado esta deuda (muchos a muchos)
    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(Payment::class)
            ->withPivot('amount')
            ->withTimestamps();
    }

    // Filtra las deudas que aún poseen un saldo pendiente de cobro
    public function scopePending(Builder $query): Builder
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_PARTIAL]);
    }

    // Filtra las obligaciones que ya han sido solventadas en su totalidad
    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PAID);
    }
}
