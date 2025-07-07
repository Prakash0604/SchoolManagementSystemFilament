<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teacher_subject_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_subject_id')->constrained('teacher_subjects')->cascadeOnUpdate();
            $table->foreignId('grade_id')->constrained('grades')->cascadeOnUpdate();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnUpdate();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_subject_lists');
    }
};
