<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->restrictOnDelete();
            $table->foreignId('school_year_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('amount_cents');
            $table->string('reason');
            $table->date('granted_at');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['school_year_id', 'student_id']);
            $table->index('granted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
