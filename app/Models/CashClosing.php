<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashClosing extends Model
{
    protected $fillable = [
        'user_id',
        'amount_bs',
        'amount_usd',
        'total_cash_usd',
        'exchange_rate',
        'notes'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
