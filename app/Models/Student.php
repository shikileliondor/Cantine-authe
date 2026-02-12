<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RuntimeException;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'school_year_id',
        'school_class_id',
        'first_name',
        'last_name',
        'birth_date',
        'guardian_name',
        'guardian_phone',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $student): void {
            if (! $student->school_year_id) {
                $student->school_year_id = SchoolYear::query()->active()->value('id');
            }

            if (! $student->school_year_id) {
                throw new RuntimeException('Aucune année scolaire active disponible.');
            }
        });
    }

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class);
    }
}
