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
        Schema::create('academic_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnUpdate();
            $table->foreignId('grade_id')->constrained('grades')->cascadeOnUpdate();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnUpdate();
            $table->foreignId('student_id')->constrained('students')->cascadeOnUpdate();
            $table->string('roll_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_information');
    }
};
