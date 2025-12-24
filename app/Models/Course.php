<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Course extends Model
{
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'name',
        'monthly_fee',
        'schedule',
        'status'
    ];

    protected $casts = [
        'monthly_fee' => 'decimal:2',
        'status' => 'string',
    ];

    // Relación de muchos a muchos con estudiantes, incluyendo datos adicionales de la inscripción
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class)
            ->withPivot('joined_at')
            ->withTimestamps();
    }

    // Filtra únicamente los cursos que se encuentran disponibles para inscripción
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }
}
