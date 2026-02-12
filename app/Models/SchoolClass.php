<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RuntimeException;

class SchoolClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_year_id',
        'name',
        'canteen_amount_cents',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $class): void {
            if (! $class->school_year_id) {
                $class->school_year_id = SchoolYear::query()->active()->value('id');
            }

            if (! $class->school_year_id) {
                throw new RuntimeException('Aucune année scolaire active disponible.');
            }
        });
    }

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
