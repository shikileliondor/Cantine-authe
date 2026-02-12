<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_year_id')->constrained()->restrictOnDelete();
            $table->foreignId('school_class_id')->constrained()->restrictOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date')->nullable();
            $table->string('guardian_name');
            $table->string('guardian_phone', 30);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_year_id', 'school_class_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
