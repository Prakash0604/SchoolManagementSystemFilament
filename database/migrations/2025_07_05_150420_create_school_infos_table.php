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
        Schema::create('school_infos', function (Blueprint $table) {
            $table->id();
            $table->string('school_name');
            $table->string('email');
            $table->string('address');
            $table->string('phone');
            $table->string('logo')->nullable();
            $table->string('website')->nullable();
            $table->string('slogan')->nullable();
            $table->string('school_theme')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_infos');
    }
};
