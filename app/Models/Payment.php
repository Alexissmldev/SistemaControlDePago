<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, BelongsToMany};

class Payment extends Model
{
    protected $fillable = [
        'student_id',
        'total_amount',
        'receipt_number',
        'user_id',
        'exchange_rate'
    ];

    protected $casts = [
        'total_amount'  => 'decimal:2',
        'exchange_rate' => 'decimal:2',
    ];

    // Accessor para asegurar que el monto no se redondee
    public function getTotalAmountAttribute($value)
    {
        return floor((float)$value * 100) / 100;
    }

    public function paymentMethods(): HasMany
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public function debts(): BelongsToMany
    {
        return $this->belongsToMany(Debt::class, 'debt_payment')
            ->withPivot('amount')
            ->withTimestamps();
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
