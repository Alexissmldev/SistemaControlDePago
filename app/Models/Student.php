<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany};
use Carbon\Carbon;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'dni',
        'birthdate',
        'representative',
        'phone',
        'status',
    ];

    protected $casts = [
        'birthdate' => 'date', // Laravel lo convierte a Carbon automáticamente si no es null
    ];

    // Accessor seguro para la edad
    public function getAgeAttribute(): int
    {
        // Si no hay fecha, devolvemos 0 en lugar de romper el sistema
        return $this->birthdate ? $this->birthdate->age : 0;
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getTotalDebtAttribute(): float
    {
        $total = $this->debts()
            ->whereIn('status', ['pending', 'partial'])
            ->sum('amount');

        return floor((float)$total * 100) / 100;
    }

    public function getMonthStatus($month, $year): array
    {
        $debts = $this->debts->filter(
            fn($d) =>
            $d->created_at->month == $month && $d->created_at->year == $year
        );

        if ($debts->isEmpty()) {
            $isPast = Carbon::create($year, $month)->isBefore(now()->startOfMonth());
            return [
                'text'  => $isPast ? 'NO INSCRITO' : '---',
                'class' => 'bg-gray-50 text-gray-400',
                'icon'  => 'fa-circle'
            ];
        }

        if ($debts->where('status', 'pending')->isNotEmpty()) {
            return [
                'text'  => 'DEBE',
                'class' => 'bg-red-100 text-red-700',
                'icon'  => 'fa-exclamation-circle'
            ];
        }

        if ($debts->where('status', 'partial')->isNotEmpty()) {
            return [
                'text'  => 'ABONO',
                'class' => 'bg-orange-100 text-orange-700',
                'icon'  => 'fa-adjust'
            ];
        }

        return [
            'text'  => 'SOLVENTE',
            'class' => 'bg-green-100 text-green-700',
            'icon'  => 'fa-check-circle'
        ];
    }

    public function scopeSearch($query, $term)
    {
        return $query->when($term, function ($q) use ($term) {
            $q->where(
                fn($sub) =>
                $sub->where('first_name', 'like', "%{$term}%")
                    ->orWhere('last_name', 'like', "%{$term}%")
                    ->orWhere('dni', 'like', "%{$term}%")
            );
        });
    }

    public function scopeFilterByCourse($query, $courseId)
    {
        return $query->when(
            $courseId,
            fn($q) =>
            $q->whereHas('courses', fn($c) => $c->where('courses.id', $courseId))
        );
    }

    public function scopeFilterByStatus($query, $status)
    {
        return $query->when($status, function ($q) use ($status) {
            $condition = $status === 'debtors' ? 'whereHas' : 'whereDoesntHave';
            $q->$condition('debts', fn($d) => $d->whereIn('status', ['pending', 'partial']));
        });
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class)->withPivot('joined_at');
    }

    public function debts(): HasMany
    {
        return $this->hasMany(Debt::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
