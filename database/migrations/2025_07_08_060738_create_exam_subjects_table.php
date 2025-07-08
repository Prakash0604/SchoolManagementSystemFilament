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
        Schema::create('exam_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_exam_id')->constrained('grade_exams')->cascadeOnUpdate();
            $table->foreignId('grade_subject_id')->constrained('grade_subjects')->cascadeOnUpdate();
            $table->string('full_mark');
            $table->string('pass_mark');
            $table->string('total_mark');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_subjects');
    }
};
