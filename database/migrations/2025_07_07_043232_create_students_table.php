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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->string('date_of_birth');
            $table->enum('gender',['Male','Female','Others']);
            $table->string('nationality');
            $table->string('profile');
            $table->string('phone');
            $table->string('address');
            $table->string('city');
            $table->string('country');
            $table->string('admission_date');
            $table->string('previous_school')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
