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
Schema::create('class_student', function (Blueprint $table) {
    $table->id();
    $table->foreignId('client_id')->constrained()->restrictOnDelete();
    $table->unsignedBigInteger('class_id');
    $table->unsignedBigInteger('student_id');
    $table->string('academic_year', 20);
    $table->timestamps();

    $table->foreign(['client_id', 'class_id'])
          ->references(['client_id', 'id'])->on('classes')->restrictOnDelete();

    $table->foreign(['client_id', 'student_id'])
          ->references(['client_id', 'id'])->on('students')->restrictOnDelete();

    $table->unique(['client_id', 'class_id', 'student_id', 'academic_year']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_student');
    }
};
