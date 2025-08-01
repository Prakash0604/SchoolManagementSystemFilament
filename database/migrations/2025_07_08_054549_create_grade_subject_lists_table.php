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
        Schema::create('grade_subject_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_subject_id')->constrained('grade_subjects')->cascadeOnUpdate();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_subject_lists');
    }
};
