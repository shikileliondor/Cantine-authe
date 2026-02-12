<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RuntimeException;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'school_year_id',
        'amount_cents',
        'paid_at',
        'period',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'paid_at' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $payment): void {
            if (! $payment->school_year_id) {
                $payment->school_year_id = SchoolYear::query()->active()->value('id');
            }

            if (! $payment->school_year_id) {
                throw new RuntimeException('Aucune année scolaire active disponible.');
            }
        });

        static::deleting(function (): void {
            throw new RuntimeException('Les versements ne peuvent pas être supprimés.');
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
