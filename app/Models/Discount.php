<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RuntimeException;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'school_year_id',
        'amount_cents',
        'reason',
        'granted_at',
        'notes',
    ];

    protected $casts = [
        'granted_at' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $discount): void {
            if (! $discount->school_year_id) {
                $discount->school_year_id = SchoolYear::query()->active()->value('id');
            }

            if (! $discount->school_year_id) {
                throw new RuntimeException('Aucune année scolaire active disponible.');
            }
        });

        static::deleting(function (): void {
            throw new RuntimeException('Les remises ne peuvent pas être supprimées.');
        });
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
