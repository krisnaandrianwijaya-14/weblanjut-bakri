<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->string('academic_year', 20);
            $table->string('component', 30);          // tugas, kuis, uts, uas
            $table->decimal('score', 5, 2);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign(['client_id', 'student_id'])
                  ->references(['client_id', 'id'])->on('students')->restrictOnDelete();

            $table->foreign(['client_id', 'subject_id'])
                  ->references(['client_id', 'id'])->on('subjects')->restrictOnDelete();

            $table->index(['client_id', 'student_id', 'academic_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
