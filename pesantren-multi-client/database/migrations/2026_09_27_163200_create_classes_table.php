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
Schema::create('classes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('client_id')->constrained()->restrictOnDelete();
    $table->string('name', 80);
    $table->string('academic_year', 20);      // 2025/2026
    $table->string('level', 30)->nullable();  // Ula, Wustho, Ulya
    $table->unsignedBigInteger('homeroom_teacher_id')->nullable();
    $table->timestamps();

    $table->unique(['client_id', 'name', 'academic_year']);
    $table->unique(['client_id', 'id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
